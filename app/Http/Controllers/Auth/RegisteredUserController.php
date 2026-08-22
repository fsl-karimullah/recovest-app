<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create Organization dynamically from user input
        $organization = Organization::create([
            'id' => Str::uuid(),
            'name' => $request->company_name,
            'currency' => 'IDR',
        ]);

        // 2. Create Default Chart of Accounts for the Organization
        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $organization->id,
            'account_code' => '101-CASH',
            'account_name' => 'Kas Utama Perusahaan',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $organization->id,
            'account_code' => '102-BANK',
            'account_name' => 'Bank Operasional',
            'account_type' => 'ASSET',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $organization->id,
            'account_code' => '401-SALES',
            'account_name' => 'Pendapatan Penjualan & Layanan',
            'account_type' => 'REVENUE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'id' => Str::uuid(),
            'organization_id' => $organization->id,
            'account_code' => '501-OPS',
            'account_name' => 'Beban Operasional Perusahaan',
            'account_type' => 'EXPENSE',
            'balance' => 0.00,
            'is_active' => true,
        ]);

        // 3. Create User linked to Organization
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $organization->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
