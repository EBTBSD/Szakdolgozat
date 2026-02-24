@extends('public.main_layout')
@section('dynamic_content')
@section('title', 'Edu:Course')
@section('site_name', 'Edu:Course')

<?php
use App\Models\User;
use App\Models\UsersModel;

$user = Auth::user();
?>
<div class="div_main_course">
    <div class="div_form_new_assigment">
        <button id="myBtn" class="btn_form_new_assigment">
            Új Feladat
        </button>
    </div>
    <div class="div_assignment_main">
        <hr>
        <?php foreach($assigments as $item): {
            if($item->creator_username == $user->username && $id == $item->course_id): {?>
            <div class="div_assignment_foreach">
                <h3><?php echo $item->assignment_name ." | ". $item->assignment_type ?></h3>
                <p><?php echo "Pontszám: ". $item->assignment_succed_point ."/".$item->assignment_max_point ?></p>
                <p><?php echo "Osztályzat: " . $item->assignment_grade ."/5" ?></p>
                <p><?php
                    switch ($item->assignment_finnished) {
                        case '2':
                            echo "Időben Leadva";
                            break;
                        case '1':
                            echo "Nem Lett Teljesítve";
                            break;
                        case '0':
                            echo "Későn Leadva";
                        default:
                            echo "Nincs Leadva";
                            break;
                    }
                ?></p>
            </div>
            <?php } endif ?>
        <?php } endforeach ?>
    </div>

    <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Új feladat létrehozása</h2>
      <form action="{{--route('coruses.newAssignemnt')--}}" method="POST" id="applyForm">
        @csrf
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="course_name">Feladat Neve:</label>
            <input class="input_form_modal" type="text" id="course_name" name="course_name" placeholder="Történelem" required>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="course_type">Feladat típusa:</label>
            <input class="input_form_modal" type="text" id="course_type" name="course_type" placeholder="Humán" required>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="course_type">Maximális pontszám:</label>
            <input class="input_form_modal" type="number" id="course_type" name="course_type" placeholder="Humán" required>
        </div>
        <div class="div_group_modal">
            <label class="lbl_form_modal" for="creator_username">Kurzus Létrehozója: </label>
            <input class="input_form_modal" type="text" id="creator_username" name="creator_username" value="{{$user->username}}" required readonly>
        </div>
        <button type="submit" class="btn_form_modal">Létrehozás</button>
    </form>
    </div>
    </div>
</div>
@endsection
