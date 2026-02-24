<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Enquiries
            'view-enquiries', 'create-enquiry', 'update-enquiry', 'delete-enquiry', 'assign-enquiry', 'manage-follow-ups',
            // Admissions
            'view-admissions', 'create-admission', 'update-admission', 'approve-admission', 'reject-admission', 'admit-student', 'verify-documents',
            // Batches
            'view-batches', 'manage-batches', 'assign-batch-faculty', 'transfer-student',
            // Students
            'view-students', 'create-student', 'update-student', 'delete-student', 'suspend-student',
            // Faculty
            'view-faculty', 'create-faculty', 'update-faculty', 'delete-faculty',
            // Subjects
            'view-subjects', 'manage-subjects',
            // Syllabus
            'view-syllabus', 'manage-syllabus', 'update-syllabus-coverage',
            // Exams
            'view-exams', 'create-exam', 'update-exam', 'delete-exam', 'publish-exam', 'view-all-results', 'take-exam',
            // Questions
            'view-questions', 'manage-questions', 'bulk-import-questions',
            // Fees
            'view-fees', 'manage-fee-structures', 'record-fee-payment', 'grant-concession', 'view-fee-reports', 'pay-fees',
            // Attendance
            'view-attendance', 'mark-attendance', 'view-attendance-reports', 'manage-leaves', 'apply-leave',
            // Materials
            'view-materials', 'manage-study-materials', 'download-materials',
            // Analytics
            'view-analytics', 'export-reports',
            // IAS
            'view-ias-submissions', 'manage-ias-evaluations', 'submit-ias-answer', 'view-ias-feedback',
            // Communication
            'send-announcements', 'send-bulk-sms', 'send-bulk-email',
            // Settings
            'manage-settings', 'manage-roles', 'manage-institutes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin - all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'view-enquiries', 'create-enquiry', 'update-enquiry', 'delete-enquiry', 'assign-enquiry', 'manage-follow-ups',
            'view-admissions', 'create-admission', 'update-admission', 'approve-admission', 'reject-admission', 'admit-student', 'verify-documents',
            'view-batches', 'manage-batches', 'assign-batch-faculty', 'transfer-student',
            'view-students', 'create-student', 'update-student', 'delete-student', 'suspend-student',
            'view-faculty', 'create-faculty', 'update-faculty', 'delete-faculty',
            'view-subjects', 'manage-subjects',
            'view-syllabus', 'manage-syllabus', 'update-syllabus-coverage',
            'view-exams', 'create-exam', 'update-exam', 'delete-exam', 'publish-exam', 'view-all-results',
            'view-questions', 'manage-questions', 'bulk-import-questions',
            'view-fees', 'manage-fee-structures', 'record-fee-payment', 'grant-concession', 'view-fee-reports',
            'view-attendance', 'mark-attendance', 'view-attendance-reports', 'manage-leaves',
            'view-materials', 'manage-study-materials', 'download-materials',
            'view-analytics', 'export-reports',
            'view-ias-submissions', 'manage-ias-evaluations',
            'send-announcements', 'send-bulk-sms', 'send-bulk-email',
            'manage-settings',
        ]);

        // Counsellor
        $counsellor = Role::firstOrCreate(['name' => 'counsellor']);
        $counsellor->syncPermissions([
            'view-enquiries', 'create-enquiry', 'update-enquiry', 'assign-enquiry', 'manage-follow-ups',
            'view-admissions', 'create-admission', 'update-admission',
            'view-students',
        ]);

        // Faculty
        $faculty = Role::firstOrCreate(['name' => 'faculty']);
        $faculty->syncPermissions([
            'view-batches', 'view-students',
            'view-syllabus', 'update-syllabus-coverage',
            'view-exams', 'create-exam', 'update-exam', 'publish-exam', 'view-all-results',
            'view-questions', 'manage-questions',
            'view-attendance', 'mark-attendance', 'view-attendance-reports',
            'view-materials', 'manage-study-materials',
            'view-ias-submissions', 'manage-ias-evaluations',
        ]);

        // Student
        $student = Role::firstOrCreate(['name' => 'student']);
        $student->syncPermissions([
            'view-syllabus', 'take-exam', 'view-attendance', 'apply-leave',
            'view-materials', 'download-materials', 'pay-fees', 'view-fees',
            'submit-ias-answer', 'view-ias-feedback',
        ]);

        // Parent
        $parent = Role::firstOrCreate(['name' => 'parent']);
        $parent->syncPermissions([
            'view-attendance', 'view-fees',
        ]);
    }
}
