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
            'break_times.*.break_start' => 'required|date_format:H:i|after:clock_in|before_or_equal:clock_out',
            'break_times.*.break_end' => 'required|date_format:H:i|after:break_times.*.break_start|before_or_equal:clock_out',
            'reason' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_in.date_format' => '出勤時間は「HH:MM」(半角)の形式で入力してください',
            'clock_out.required' => '退勤時間を入力してください',
            'clock_out.date_format' => '退勤時間は「HH:MM」(半角)の形式で入力してください',
            'break_times.*.break_start.required' => '休憩開始時間を入力してください',            
            'break_times.*.break_start.date_format' => '休憩時間は「HH:MM」(半角)の形式で入力してください',            
            'break_times.*.break_end.date_format' => '休憩時間は「HH:MM」(半角)の形式で入力してください',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_times.*.break_end.required' => '休憩終了時間を入力してください',
            'break_times.*.break_end.after' => '休憩終了時間は開始時間より後である必要があります',
            'break_times.*.break_start.after' => '休憩開始時刻は出勤時間より後である必要があります',
            'break_times.*.break_start.before_or_equal' => '休憩開始時間は退勤時間より前である必要があります',            
            'break_times.*.break_end.before_or_equal' => '休憩終了時間は退勤時間より前である必要があります',           
            'reason.required' => '備考を記入してください',
        ];
    }
}