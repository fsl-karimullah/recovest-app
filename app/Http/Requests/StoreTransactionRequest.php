<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_date' => ['required', 'date'],
            'type' => ['required', 'in:INCOME,EXPENSE,TRANSFER'],
            'amount' => ['required', 'numeric', 'min:1'],
            'chart_of_account_id' => ['required', 'uuid', 'exists:chart_of_accounts,id'],
            'bank_connection_id' => ['nullable', 'uuid', 'exists:bank_connections,id'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'proof_attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
