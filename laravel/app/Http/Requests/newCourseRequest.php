<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class newCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "course_name" => "",
            "course_type" => "",
            "course_img_path" => "",
            "creator_username" => "",
        ];

        /*<div class="div_group_modal">
            <label class="lbl_form_modal" for="course_name">Kurzus Neve:</label>
            <input class="input_form_modal" type="text" id="course_name" name="course_name" placeholder="Történelem" required>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="course_type">Kurzus típusa:</label>
            <input class="input_form_modal" type="text" id="course_type" name="course_type" placeholder="Humán" required>
        </div>
        <div class="div_group_modal div_group_modal_new">
            <label class="lbl_form_modal lbl_form_modal_new" for="course_img_path">Kurzus képe:</label>
            <br>
            <?php for ($i=1; $i < 9; $i++): { ?>
                <input type="radio" class="checkbox_form_modal checkbox_form_modal_new" type="text" id="img_{{$i}}" name="course_img_path" style="background-image: url('/images/course_images/{{$i}}.jpg');" required>
            <?php } endfor ?>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="creator_username">Kurzus Létrehozója: </label>
            <input class="input_form_modal" type="text" id="creator_username" name="creator_username" value="{{$user->username}}" required readonly>
        </div>*/
    }
}
