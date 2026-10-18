<?php

namespace App\Http\Requests;

use App\Models\Kategori;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PemasukanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->role === 'mahasiswa';
    }

    public function rules(): array
    {
        $categories = Kategori::query()
            ->where('type', 'income')
            ->pluck('name')
            ->push('Lainnya')
            ->unique()
            ->values()
            ->all();

        return [
            'user_id' => ['prohibited'],
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', Rule::in($categories)],
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:9999999999.99',
            ],
            'transaction_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}