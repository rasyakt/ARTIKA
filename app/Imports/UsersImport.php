<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\IdentityType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $roles;
    private $identityTypes;

    public function __construct()
    {
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
                $data[$key] = (string) $value;
            }
        }
        return $data;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip rows where username or full name is empty
            if (empty($row['username']) || empty($row['nama_lengkap'])) {
                continue;
            }

            $roleName = strtolower($row['nama_role'] ?? 'cashier');
            $roleId = $this->roles[$roleName] ?? ($this->roles['cashier'] ?? Role::where('name', 'Cashier')->first()?->id);

            $identityName = strtolower($row['jenis_identitas'] ?? 'nis');
            $identityTypeId = $this->identityTypes[$identityName] ?? null;

            $userData = [
                'name' => $row['nama_lengkap'],
                'nis' => $row['nomor_identitas'] ?? null,
                'role_id' => $roleId,
                'identity_type_id' => $identityTypeId,
            ];

            // Only update password if provided
            if (!empty($row['password'])) {
                $userData['password'] = Hash::make($row['password']);
            }

            User::updateOrCreate(
                ['username' => $row['username']],
                $userData
            );
        }
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'nomor_identitas' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'nama_role' => ['nullable', 'string'],
            'jenis_identitas' => ['nullable', 'string'],
        ];
    }
}
