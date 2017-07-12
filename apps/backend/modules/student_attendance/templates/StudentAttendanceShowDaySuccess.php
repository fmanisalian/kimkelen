<?php /*
 * Kimkëlen - School Management Software
 * Copyright (C) 2013 CeSPI - UNLP <desarrollo@cespi.unlp.edu.ar>
 *
 * This file is part of Kimkëlen.
 *
 * Kimkëlen is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v2.0 as published by
 * the Free Software Foundation.
 *
 * Kimkëlen is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kimkëlen.  If not, see <http://www.gnu.org/licenses/gpl-2.0.html>.
 */ ?>
<?php use_stylesheet('/sfPropelRevisitedGeneratorPlugin/css/global.css') ?>
<?php use_stylesheet('/sfPropelRevisitedGeneratorPlugin/css/extended.css') ?>
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<div id="sf_admin_container">
  <h1>
    <?php echo __($title, array('%division%' => $form->getDivision(), '%subject%' => $form->getCourseSubject())) ?>
  </h1>
  <div id="sf_admin_content">
    <?php include_partial('division/information_box') ?>
    <form action="<?php echo url_for('@save_student_attendance_show_day') ?>" method="post" >
      <ul class="sf_admin_actions">
        <li class ="sf_admin_action_list"><?php echo link_to(__('Back'), '@student_attendance_day_show_day'); ?></li>
        <li class ="sf_admin_action_list"><input type="submit" value="<?php echo __('Save', array(), 'sf_admin') ?>" /></li> 
      </ul>
      
      <div class="week_move">
		<?php (date('l', strtotime($form->day)) == 'Friday') ? $next_day = date('Y-m-d', strtotime($form->day . '+ 3 day')) : $next_day = date('Y-m-d', strtotime($form->day . '+ 1 day')) ?>
        <?php (date('l', strtotime($form->day)) == 'Monday') ? $previous_day = date('Y-m-d', strtotime($form->day . '- 3 day')) : $previous_day = date('Y-m-d', strtotime($form->day . '- 1 day')) ?>

        <?php echo image_tag('../sfPropelPlugin/images/previous.png') ?>
        <?php echo link_to(__('previous day'), 'student_attendance/StudentAttendanceShowDay', array('query_string' => "year=$form->year&career_school_year_id=$form->career_school_year_id&division_id=$form->division_id&course_subject_id=$form->course_subject_id&day=" . $previous_day)); ?>
        <?php echo link_to(__('next day'), 'student_attendance/StudentAttendanceShowDay', array('query_string' => "year=$form->year&career_school_year_id=$form->career_school_year_id&division_id=$form->division_id&course_subject_id=$form->course_subject_id&day=" . $next_day )); ?>
        <?php echo image_tag('../sfPropelPlugin/images/next.png') ?>
      </div>

      <?php if (!$form->isAttendanceBySubject()): ?>
        <div class="division_move">
          <?php $next_division = $form->getNextDivision() ?>
          <?php $previous_division = $form->getPreviousDivision() ?>

          <input type="submit" value="<?php echo __('Assistance to %division%', array('%division%' => $previous_division)) ?>" onClick="return confirm('Se guardarán los cambios antes de cambiar de división. ¿Está seguro que quiere abandonar la página?')" name="previous_division"/>
          <input type="submit" value="<?php echo __('Assistance to %division%', array('%division%' => $next_division)) ?>" onClick="return confirm('Se guardarán los cambios antes de cambiar de división. ¿Está seguro que quiere abandonar la página?')" name="next_division"/>
        </div>
      <?php endif ?>

      <?php echo $form->renderHiddenFields() ?>
      <?php echo $form->renderGlobalErrors() ?>
      <?php $course_subject_id = isset($form->course_subject_id) ? $form->course_subject_id : null ?>
      <?php $course_subject = CourseSubjectPeer::retrieveByPK($course_subject_id); ?>
      <?php $career_school_year = $form->getCareerSchoolYear() ?>

      <?php $absence_for_period = $form->isAbsenceForPeriod(); ?>

      <?php if ($absence_for_period): ?>
        <?php $career_school_year_periods = $form->getCareerSchoolYearPeriods(); ?>
        <?php $count_career_school_year_period = count($career_school_year_periods) ?>
      <?php endif ?>

      <table id="student_attendance">
        <thead>
          <tr>
            <td></td>
            <td>
                <?php $name = 'day_disabled_1'  ?>
                <?php echo $form[$name]; ?>
                <?php echo __("Disabled"); ?>
                <?php if ($form->getDefault($name)): ?>
                  <script>disableDay(1)</script>
                <?php endif ?>
                <?php if (HolidayPeer::isHoliday($form->day)): ?>
                  <script>disableDayUneditable(1)</script>
                <?php endif; ?>      
                
            </td>
            <?php if ($absence_for_period): ?>
              <td colspan="<?php echo $count_career_school_year_period ?>"></td>
              <td colspan="<?php echo $count_career_school_year_period ?>"></td>
              <td colspan="<?php echo $count_career_school_year_period ?>"></td>
            <?php else: ?>
              <td></td>
              <td></td>
              <td></td>
            <?php endif ?>
            <td><?php echo __("Actions") ?></td>
          </tr>
          <tr>
            <td><?php echo __('Student'); ?></td>
            
              <td>
                <?php echo __(date('l', strtotime($form->day))); ?>
                <?php echo date('d/m', strtotime($form->day)); ?>
              </td>
           

            <?php if ($absence_for_period): ?>
              <?php foreach ($career_school_year_periods as $career_school_year_period): ?>
                <td class="period" colspan="3"><?php echo $career_school_year_period->getShortName() ?></td>
              <?php endforeach ?>
            <?php else: ?>
              <td colspan="3"></td>
            <?php endif ?>

            <td></td>
          </tr>
          <tr>
            <td></td>
            <td></td>

            <?php if ($absence_for_period): ?>
              <?php if ($count_career_school_year_period): ?>

                <?php for ($i = 1; $i <= $count_career_school_year_period; $i++): ?>
                  <td><?php echo __("SJ") ?></td>
                  <td><?php echo __("J") ?></td>
                  <td><?php echo __("T") ?></td>
                <?php endfor ?>
              <?php else: ?>
                <td><?php echo __("El curso/división no posee configuración de asistencias") ?></td>
                <td></td>
                <td></td>
              <?php endif ?>
            <?php else: ?>
              <td><?php echo __("SJ") ?></td>
              <td><?php echo __("J") ?></td>
              <td><?php echo __("T") ?></td>
            <?php endif ?>
            <td></td>

          </tr>
        </thead>
        <tbody>

          <?php foreach ($form->students as $student): ?>
            <tr>
              <td class="<?= $student->getHealthCardStatusAttendanceClass()?>" ><?php echo $student ?></td>
              
                <td class="day_1 <?php echo $student->getClassForJustificatedAbsencesPerSubjectAndDay($career_school_year,$form->day,$course_subject_id)?>">
                  <?php $name = 'student_attendance_' . $student->getId() . '_' . 1 ?>

                  <?php echo $form[$name] ?>
                  <?php if ($form[$name]->hasError()): ?>
                    <?php echo $form[$name]->renderError() ?>
                  <?php endif ?>
                </td>
             
              <?php if ($absence_for_period): ?>
                <?php foreach ($career_school_year_periods as $career_school_year_period): ?>
                  <?php $free_class = $student->getFreeClass($career_school_year_period, $course_subject, $career_school_year, $form->getDivision()) ?>

                  <td class="<?php echo $free_class ?>"><?php echo $student->getTotalAbsences($form->career_school_year_id, $career_school_year_period, $course_subject_id) ?></td>
                  <td class="<?php echo $free_class ?>"><?php echo $student->getTotalJustificatedAbsences($form->career_school_year_id, $career_school_year_period, $course_subject_id, false) ?></td>
                  <td class="<?php echo $free_class ?>"><?php echo $student->getTotalAbsences($form->career_school_year_id, $career_school_year_period, $course_subject_id, false) ?></td>
                <?php endforeach ?>
              <?php else: ?>
                <?php $free_class = $student->getFreeClass(null, $course_subject, $career_school_year) ?>
                <td class="<?php echo $free_class ?>"><?php echo $student->getTotalAbsences($form->career_school_year_id, null, $course_subject_id) ?></td>
                <td class="<?php echo $free_class ?>"><?php echo $student->getTotalJustificatedAbsences($form->career_school_year_id, null, $course_subject_id, false) ?></td>
                <td class="<?php echo $free_class ?>"><?php echo $student->getTotalAbsences($form->career_school_year_id, null, $course_subject_id, false) ?></td>
              <?php endif ?>

              <td><?php include_partial("student_attendance/actions", array('student' => $student, 'course_subject' => $course_subject, 'career_school_year' => $career_school_year)) ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <ul class="sf_admin_actions">
        <li class ="sf_admin_action_list"><?php echo link_to(__('Back'), '@student_attendance_day_show_day'); ?></li>
        <li ><input type="submit" value="<?php echo __('Save', array(), 'sf_admin') ?>" /></li>
      </ul>
    </form>
  </div>
</div>
<script>
    disableColumn(1);
</script>
