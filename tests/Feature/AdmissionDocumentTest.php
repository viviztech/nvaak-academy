<?php

namespace Tests\Feature;

use App\Livewire\Public\Admission\AdmissionForm;
use App\Models\Admission;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdmissionDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admission_uploads_are_stored_privately(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $this->institute();

        Livewire::test(AdmissionForm::class)
            ->set('first_name', 'Test')
            ->set('last_name', 'Applicant')
            ->set('date_of_birth', '2005-01-01')
            ->set('gender', 'female')
            ->set('phone', '9000000000')
            ->set('father_name', 'Test Guardian')
            ->set('father_phone', '9000000001')
            ->set('photo', UploadedFile::fake()->image('photo.jpg'))
            ->set('aadhaar_document', UploadedFile::fake()->create('identity.pdf', 1, 'application/pdf'))
            ->set('marksheet', UploadedFile::fake()->create('marksheet.pdf', 1, 'application/pdf'))
            ->call('submit')
            ->assertHasNoErrors();

        $admission = Admission::sole();
        $this->assertCount(3, $admission->documents);

        foreach ($admission->documents as $path) {
            Storage::disk('private')->assertExists($path);
            Storage::disk('public')->assertMissing($path);
        }
    }

    public function test_an_admin_can_download_a_document_for_their_institute(): void
    {
        $admission = $this->admission();
        $admin = $this->userWithRole('admin', $admission->institute_id);

        $this->actingAs($admin)
            ->get(route('admin.admissions.documents.download', [$admission, 'aadhaar']))
            ->assertOk()
            ->assertDownload('identity.pdf');
    }

    public function test_guests_and_students_cannot_download_admission_documents(): void
    {
        $admission = $this->admission();
        $url = route('admin.admissions.documents.download', [$admission, 'aadhaar']);

        $this->get($url)->assertRedirect(route('login'));

        $student = $this->userWithRole('student', $admission->institute_id);
        $this->actingAs($student)->get($url)->assertForbidden();
    }

    public function test_an_admin_cannot_download_another_institutes_documents(): void
    {
        $admission = $this->admission();
        $otherInstitute = $this->institute('other-academy');
        $admin = $this->userWithRole('admin', $otherInstitute->id);

        $this->actingAs($admin)
            ->get(route('admin.admissions.documents.download', [$admission, 'aadhaar']))
            ->assertForbidden();
    }

    public function test_missing_documents_and_paths_outside_the_admission_are_rejected(): void
    {
        $admission = $this->admission();
        $admin = $this->userWithRole('admin', $admission->institute_id);

        $this->actingAs($admin)
            ->get(route('admin.admissions.documents.download', [$admission, 'missing']))
            ->assertNotFound();

        Storage::disk('private')->put('other-sensitive-file.pdf', 'private');
        $admission->update(['documents' => ['aadhaar' => 'other-sensitive-file.pdf']]);

        $this->get(route('admin.admissions.documents.download', [$admission, 'aadhaar']))
            ->assertNotFound();
    }

    private function institute(string $slug = 'nvaak-academy'): Institute
    {
        return Institute::create(['name' => 'Test Academy', 'slug' => $slug]);
    }

    private function admission(): Admission
    {
        Storage::fake('private');
        $institute = $this->institute();
        $path = 'admissions/APP2026000001/aadhaar/identity.pdf';
        Storage::disk('private')->put($path, 'test document');

        return Admission::create([
            'institute_id' => $institute->id,
            'application_number' => 'APP2026000001',
            'first_name' => 'Test',
            'last_name' => 'Applicant',
            'phone' => '9000000000',
            'documents' => ['aadhaar' => $path],
        ]);
    }

    private function userWithRole(string $role, int $instituteId): User
    {
        Role::findOrCreate($role, 'web');
        $user = User::factory()->create(['institute_id' => $instituteId]);
        $user->assignRole($role);

        return $user;
    }
}
