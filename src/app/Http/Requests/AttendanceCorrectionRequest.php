<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCorrectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i|after:clock_in',
            'break_times.*.break_start' => 'nullable|date_format:H:i',
            'break_times.*.break_end' => 'nullable|date_format:H:i|after:break_times.*.break_start',
            'reason' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_in.date_format' => '出勤時間は「HH:MM」の形式で入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.date_format' => '退勤時間は「HH:MM」の形式で入力してください',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_times.*.break_start.date_format' => '休憩時間は「H:MM」の形式で入力してください',
            'break_times.*.break_end.date_format' => '休憩時間は「H:MM」の形式で入力してください',
            'break_times.*.break_end.after' => '休憩時間が勤務時間外です',
            'reason.required' => '備考を記入してください'
        ];
    }
    
}
