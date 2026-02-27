<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\IdentityType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $roles;
    private $identityTypes;

    public function __construct()
    {
        // Cache roles and identity types to avoid N+1 queries during bulk import
        $this->roles = Role::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        });

        $this->identityTypes = IdentityType::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        });
    }

    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            if ($value !== null && !is_array($value)) {
                // Cast all scalar values to string to avoid "validation.string" and "max" errors on numeric formats
                $data[$key] = (string) $value;
            }
        }
        return $data;
    }

    public function model(array $row)
    {
        $roleName = strtolower($row['nama_role'] ?? 'cashier');
        $roleId = $this->roles[$roleName] ?? $this->roles['cashier'];

        $identityName = strtolower($row['jenis_identitas'] ?? 'nis');
        $identityTypeId = $this->identityTypes[$identityName] ?? null;

        return new User([
            'name' => $row['nama_lengkap'],
            'username' => $row['username'],
            'nis' => $row['nomor_identitas'],
            'password' => Hash::make($row['password']),
            'role_id' => $roleId,
            'identity_type_id' => $identityTypeId,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'nomor_identitas' => ['nullable', 'string', 'max:255', 'unique:users,nis'],
            'password' => ['required', 'string', 'min:6'],
            'nama_role' => ['required', 'string'],
            'jenis_identitas' => ['nullable', 'string'],
        ];
    }
}
