<?php

namespace App\Http\Requests;

use App\Item;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    

    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'prefix' => ['required','unique:items','max:3'],
            'hsn' => ['required'],
            'product_category_id' => ['required'],
            'tax' => ['required','between:0,99.99'],
            'cess' => ['between:0,99.99'],
        ];
    }
}
