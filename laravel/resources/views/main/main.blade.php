<?php
// use Illuminate\Support\Facades\Auth;
// $user = Auth::user();
use App\Models\User;
use App\Models\UsersModel;

$user = Auth::user();    // vagy User::find($id);
?>

@extends('public.main_layout')
@section('dynamic_content')
@section('title', 'Edu:Score')
@section('site_name', 'Edu:Score')
<div class="container">
    <aside class="div_all_course_div">
        <div class="btn_new_course">
        <button id="myBtn" class="newCourse_Modal">
            Új Kurzus
        </button>
        </div>
        <?php foreach ($courses as $item): {
            if($item->course_users == $user->username): {?>

            <?php } elseif($item->creator_username == $user->username): {?>
            <a href="{{ route('courses.courses', ['id' => $item->id]) }}">
                <div style="background-image: url('{{asset($item->course_img_path)}}');" class="div_course_foreach" >
                    <div class="div_course_inside">
                        <h3 class="h3_course"><?php echo $item->course_name ?></h3>
                    </div>
                </div>
            </a>
            <?php } endif ?>
        <?php } endforeach ?>
    </aside>
    <main class="content">
        <div class="div_stats">
            <div class="div_grade_avg">
                <h2 class="h2_display">Átlag</h2>
                <div class="gauge-container">
                    <div class="gauge" id="avg_gauge"></div>
                    <div class="overlay"></div>
                    <div class="text" id="avg_text">0.0</div>
                </div>
            </div>
            <div class="div_grade_avg">
                <h2 class="h2_display">Feladatok</h2>
                <div class="gauge-container">
                    <div class="gauge" id="ass_gauge"></div>
                    <div class="overlay"></div>
                    <div class="text" id="ass_text">0%</div>
                </div>
            </div>
            <div class="div_grade_avg">
                <h4 class="h4_assigment_dis">Időben Leadott Feladatok: {{$ass_perc_suc}}</h4>
                <h4 class="h4_assigment_dis">Elmulasztott feladatok: {{$ass_perc_fai}}</h4>
                <h4 class="h4_assigment_dis">Későn Leadott Feladatok {{$ass_perc_out}}</h4>
                <h4 class="h4_assigment_dis">Még Nem Leadott Feladatok {{$ass_perc_need}}</h4>
            </div>
        </div>
        <div class="div_display">
            <div class="div_event_dis">
                <div class="div_event_for">
                    Esemény #1
                </div>
                <div class="div_event_for">
                    Esemény #2
                </div>
                <div class="div_event_for">
                    Esemény #3
                </div>
            </div>
            <div class="div_assignment_dis">
                <div class="div_assignment_for">
                    Feladat #1
                </div>
                <div class="div_assignment_for">
                    Feladat #2
                </div>
                <div class="div_assignment_for">
                    Feladat #3
                </div>
            </div>
        </div>
    </main>
</div>

<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Új kurzus létrehozása</h2>
      <form action="{{route('coruses.newCoruses')}}" method="POST" id="applyForm">
        @csrf
        <div class="div_group_modal">
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
                <input type="radio" class="checkbox_form_modal checkbox_form_modal_new" type="text" id="img_{{$i}}" name="course_img_path" style="background-image: url('/images/course_images/{{$i}}.jpg');" value="images/course_images/{{$i}}.jpg" required>
            <?php } endfor ?>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="creator_username">Kurzus Létrehozója: </label>
            <input class="input_form_modal" type="text" id="creator_username" name="creator_username" value="{{$user->username}}" required readonly>
        </div>
        <button type="submit" class="btn_form_modal">Létrehozás</button>
    </form>
    </div>
  </div>

<script>
    var assignmentAverage = {{ $average }};
    var assignmentCompleted = {{$ass_perc}};
</script>
@endsection
