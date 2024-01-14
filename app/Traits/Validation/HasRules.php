<?php

namespace App\Traits\Validation;

use App\Enums\Enrollment\CertificateStatus;
use App\Enums\Enrollment\EnrollmentStatus;
use App\Enums\Enrollment\PaymentStatus;
use App\Enums\Receipt\PaymentMethod;
use App\Enums\User\Blood;
use App\Enums\User\Gender;
use App\Enums\User\Role;
use Illuminate\Validation\Rules\Enum;

trait HasRules
{
    public function commonRules()
    {
        return [
            'model' => ['user', 'category', 'course', 'enrollment', 'receipt', 'resource'],
        ];
    }

    public function storeRules()
    {
        return [
            'user' => [
                'name' => ['required', 'string'],
                'role' => ['nullable', new Enum(Role::class)],
                'gender' => ['required', new Enum(Gender::class)],
                'blood' => ['nullable', new Enum(Blood::class)],
                'email' => ['required', 'email', 'unique:users,email'],
                'phone' => [
                    'required',
                    'string',
                    'regex:/^(?:\+8801|8801|01)[3456789]\d{8}$/',
                    'unique:users,phone'],
                'emergency_phone' => ['nullable', 'regex:/^(?:\+8801|8801|01)[3456789]\d{8}$/', 'string'],
                'image' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
                'field_of_study' => ['nullable', 'string'],
            ],
            'category' => [
                'name' => ['required', 'unique:categories,name'],
            ],
            'course' => [
                'category_id' => ['required', 'exists:categories,id'],
                'title' => ['required', 'string', 'unique:courses,title'],
                'short_desc' => ['required', 'string'],
                'long_desc' => ['nullable', 'string'],
                'hours' => ['required', 'integer', 'min:0'],
                'classes' => ['required', 'integer', 'min:0'],
                'certifiable' => ['required', 'boolean'],
                'thumbnail' => ['required', 'string'],
                'fee' => ['required', 'numeric', 'min:0'],
                'discount' => ['required', 'numeric', 'min:0'],
            ],
            'enrollment' => [
                'student_id' => ['required', 'exists:users,id'],
                'course_id' => ['required', 'exists:courses,id'],
                'payment_status' => ['nullable', new Enum(PaymentStatus::class)],
                'enrolled_by' => ['nullable', 'exists:users,id'],
                // 'approved_at' => ['nullable', 'date'],
                // 'approved_by' => ['nullable', 'exists:users,id'],
                // 'certificate_requested_at' => ['nullable', 'date'],
                // 'certificate_status' => ['nullable', new Enum(CertificateStatus::class)],
                // 'certificate_handled_by' => ['nullable', 'exists:users,id'],
            ],
            'receipt' => [
                'type' => ['required', new Enum(\App\Enums\Receipt\Type::class)],
                'client' => ['required', 'string'],
                'email' => ['required', 'email'],
                'description' => ['nullable', 'string'],
                'payment_method' => ['required', new Enum(PaymentMethod::class)],
                'trx_id' => ['nullable', 'string'],
                'trx_date' => ['nullable', 'date'],
                'fee' => ['required', 'numeric', 'min:0'],
                'total' => ['required', 'numeric', 'min:0'],
                'paid' => ['required', 'numeric', 'min:0'],
                'due' => ['required', 'numeric', 'min:0'],
                'student_id' => ['nullable', 'exists:users,id'],
                'course_id' => ['nullable', 'exists:courses,id'],
                'enrollment_id' => ['nullable', 'exists:enrollments,id'],
            ],
            'resource' => [
                'name' => ['required', 'string', 'unique:resources,name'],
                'type' => ['required', new Enum(\App\Enums\Resource\Type::class)],
                'thumbnail' => ['required', 'string'],
                'location' => ['required', 'string'],
            ],
        ];
    }

    public function updateRules($id)
    {
        return [
            'user' => [
                'name' => ['required', 'string'],
                'role' => ['nullable', new Enum(Role::class)],
                'gender' => ['required', new Enum(Gender::class)],
                'blood' => ['nullable', new Enum(Blood::class)],
                'email' => ['required', 'email', 'unique:users,email,'.$id],
                'phone' => [
                    'required',
                    'string',
                    'regex:/^(?:\+8801|8801|01)[3456789]\d{8}$/',
                    'unique:users,phone,'.$id],
                'emergency_phone' => ['nullable', 'regex:/^(?:\+8801|8801|01)[3456789]\d{8}$/', 'string'],
                'image' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
                'field_of_study' => ['nullable', 'string'],
            ],
            'category' => [
                'name' => ['required', 'unique:categories,name,'.$id],
            ],
            'course' => [
                'category_id' => ['required', 'exists:categories,id'],
                'title' => ['required', 'string', 'unique:courses,title,'.$id],
                'short_desc' => ['required', 'string'],
                'long_desc' => ['nullable', 'string'],
                'hours' => ['required', 'integer', 'min:0'],
                'classes' => ['required', 'integer', 'min:0'],
                'certifiable' => ['required', 'boolean'],
                'thumbnail' => ['required', 'string'],
                'fee' => ['required', 'numeric', 'min:0'],
                'discount' => ['required', 'numeric', 'min:0'],
            ],
            'enrollment' => [
                'student_id' => ['nullable', 'exists:users,id'],
                'course_id' => ['nullable', 'exists:courses,id'],
                'payment_status' => ['nullable', new Enum(PaymentStatus::class)],
                'enrolled_by' => ['nullable', 'exists:users,id'],
                'enrollment_status' => ['nullable', new Enum(EnrollmentStatus::class)],
                'handled_at' => ['nullable', 'date'],
                'handled_by' => ['nullable', 'exists:users,id'],
                'certificate_requested_at' => ['nullable', 'date'],
                'certificate_status' => ['nullable', new Enum(CertificateStatus::class)],
                'certificate_handled_by' => ['nullable', 'exists:users,id'],
            ],
            'receipt' => [
                'type' => ['required', new Enum(\App\Enums\Receipt\Type::class)],
                'client' => ['required', 'string'],
                'email' => ['required', 'email'],
                'description' => ['nullable', 'string'],
                'payment_method' => ['required', new Enum(PaymentMethod::class)],
                'trx_id' => ['nullable', 'string'],
                'trx_date' => ['nullable', 'date'],
                'fee' => ['required', 'numeric', 'min:0'],
                'total' => ['required', 'numeric', 'min:0'],
                'paid' => ['required', 'numeric', 'min:0'],
                'due' => ['required', 'numeric', 'min:0'],
                'student_id' => ['nullable', 'exists:users,id'],
                'course_id' => ['nullable', 'exists:courses,id'],
                'enrollment_id' => ['nullable', 'exists:enrollments,id'],
            ],
            'resource' => [
                'name' => ['required', 'string', 'unique:resources,name,'.$id],
                'type' => ['required', new Enum(\App\Enums\Resource\Type::class)],
                'thumbnail' => ['required', 'string'],
                'location' => ['required', 'string'],
            ],
        ];
    }
}
