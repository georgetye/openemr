<?php
/**
 * interface/therapy_groups/therapy_groups_views/groupDetailsParticipants.php contains group participants details view .
 *
 * This is the therapy group's participants detail screen for the chosen group.
 *
 * Copyright (C) 2016 Shachar Zilbershlag <shaharzi@matrix.co.il>
 * Copyright (C) 2016 Amiel Elboim <amielel@matrix.co.il>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Shachar Zilbershlag <shaharzi@matrix.co.il>
 * @author  Amiel Elboim <amielel@matrix.co.il>
 * @link    http://www.open-emr.org
 */
?>
<?php require 'header.php'; ?>
<main id="group-details">
    <div class="container-group">
        <span class="hidden title"><?php echo text($groupName);?></span>
        <div class="row">
            <div id="main-component" class="col-md-8 col-sm-12">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <ul class="tabNav">
                            <li><a href="<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=groupDetails&group_id=' . attr($groupId); ?>"><?php echo xlt('General data');?></a></li>
                            <li class="current"><a href="<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=groupParticipants&group_id=' . attr($groupId); ?>"><?php echo xlt('Participants ');?></a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <button onclick="newGroup()"><?php echo xlt('Add encounter'); ?></button>
                        <?php if($readonly == ''): ?>
                            <button class="float-right" onclick="location.href='<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=groupParticipants&group_id=' . attr($groupId); ?>'"><?php echo xlt('Cancel');?></button>
                            <button  id="saveForm" class="float-right"><?php echo xlt('Save');?></button>
                        <?php else: ?>
                            <button class="float-right" onclick="location.href='<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=groupParticipants&editParticipants=1&group_id=' . attr($groupId); ?>'"><?php echo xlt('Update');?></button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="component-border">
                            <div  class="row">
                                <form  id="add-participant-form" name="add-participant-form" class="<?php echo isset($addStatus) ? 'showAddForm' : '' ?>" action="<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=addParticipant&group_id=' . attr($groupId)?>" method="post">
                                    <input type="hidden" id="pid" name="pid" value="<?php echo !is_null($participant_data) ? attr($participant_data['pid']): ''?>">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-5">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="bold"><?php echo xlt("Participant's name"); ?>:</span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" id="participant_name" name="participant_name" class="full-width" value="<?php echo !is_null($participant_data) ? attr($participant_data['participant_name']): ''?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <span class="bold"><?php echo xlt('Date of registration'); ?>:</span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" name="group_patient_start" class="full-width datepicker"  value="<?php echo !is_null($participant_data) ? attr($participant_data['group_patient_start']): date('Y-m-d');?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-offset-1 col-md-2">
                                                <span class="bold"><?php echo xlt('Comment'); ?>:</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="group_patient_comment" value="<?php echo !is_null($participant_data) ? attr($participant_data['group_patient_comment']): ''?>" class="full-width">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-offset-4 col-md-4 text-center">
                                                <input type="submit" name="save_new" value="<?php echo xla('Adding a participant'); ?>">
                                                <input id="cancelAddParticipant" type="button" value="<?php echo xla('Cancel'); ?>">
                                            </div>
                                        </div>
                                        <?php if(isset($message)): ?>
                                        <div class="row">
                                            <div class="col-md-offset-2 col-md-8">
                                                <p class="<?php echo $addStatus == 'failed' ? 'groups-error-msg' : 'groups-success-msg' ?>"><?php echo text($message)?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="updateParticipants" method="post">
                                        <input type="hidden" name="group_id" value="<?php echo attr($groupId); ?>" />
                                        <button id="addParticipant"><?php echo xlt('Add'); ?></button>
                                        <table  id="participants_table" class="dataTable display">
                                            <thead>
                                            <tr>
                                                <th><?php echo xlt("Participant's name"); ?></th>
                                                <th><?php echo xlt("Patient's number"); ?></th>
                                                <th><?php echo xlt('Status in the group'); ?></th>
                                                <th><?php echo xlt('Date of registration'); ?></th>
                                                <th><?php echo xlt('Date of exit'); ?></th>
                                                <th><?php echo xlt('Comment'); ?></th>
                                                <?php if($readonly == ''): ?>
                                                    <th><?php echo xlt('Delete'); ?></th>
                                                <?php endif; ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($participants as $i => $participant) : ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="pid[]" value="<?php echo htmlspecialchars($participant['pid'],ENT_QUOTES); ?>" />
                                                        <span><?php echo text($participant['lname']) .', ' . text($participant['fname']); ?></span>
                                                    </td>
                                                    <td><span><?php echo text($participant['pid']); ?></span></td>
                                                    <td>
                                                        <select name="group_patient_status[]" <?php echo $readonly; ?>>
                                                            <?php foreach ($statuses as $key => $status): ?>
                                                                <option value="<?php echo attr($key);?>" <?php if($key == $participant['group_patient_status']) echo 'selected'; ?> > <?php echo text($status); ?> </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="group_patient_start[]" id="start-date<?php echo $i+1?>" class="datepicker"  value="<?php echo attr($participant['group_patient_start']);?>" <?php echo $readonly; ?>></td>
                                                    <td><input type="text" name="group_patient_end[]" id="end-date<?php echo $i+1?>" class="datepicker" value="<?php echo $participant['group_patient_end'] == '0000-00-00' ? '' : attr($participant['group_patient_end']) ;?>" <?php echo $readonly; ?>></td>
                                                    <td><input type="text" name="group_patient_comment[]" class="full-width" class="datepicker"  value="<?php echo attr($participant['group_patient_comment']);?>" <?php echo $readonly; ?> /></td>
                                                    <?php if($readonly == ''): ?>
                                                        <td class="delete_btn">
                                                            <a href="<?php echo $GLOBALS['rootdir'] . '/therapy_groups/index.php?method=groupParticipants&group_id='. attr($groupId) .'&deleteParticipant=1&pid=' . attr($participant['pid']); ?>"><span>X</span></a>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="appointment-component" class="col-md-4 col-sm-12">
                <?php require 'appointmentComponent.php';?>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function(){
        $('.datepicker').datetimepicker({
            <?php $datetimepicker_timepicker = false; ?>
            <?php $datetimepicker_showseconds = false; ?>
            <?php $datetimepicker_formatInput = false; ?>
            <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
            <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma ?>
        });

        var table = $('#participants_table').DataTable({
            "language": {
                "lengthMenu": '<?php echo xlt("Display")  .' _MENU_  ' .xlt("records per page")?>',
                "zeroRecords": '<?php echo xlt("Nothing found - sorry")?>',
                "info": '<?php echo xlt("Showing page") .' _PAGE_ '. xlt("of") . ' _PAGES_'; ?>',
                "infoEmpty": '<?php echo xlt("No records available") ?>',
                "infoFiltered": '<?php echo "(" . xlt("filtered from") . ' _MAX_ '. xlt("total records") . ")"; ?>',
                "infoPostFix":  "",
                "search":       "<?php echo xlt('Search')?>",
                "url":          "",
                "oPaginate": {
                    "sFirst":    "<?php echo xlt('First')?>",
                    "sPrevious": "<?php echo xlt('Previous')?>",
                    "sNext":     "<?php echo xlt('Next')?>",
                    "sLast":     "<?php echo xlt('Last')?>"
                }
            },
            "columnDefs": [
                { "width": "35%", "targets": 5 }
            ],
            "pageLength":6,
            "searching": false
        });
        var countRows =table.rows().count();
        console.log(countRows);

        // validation on submit -
        // 1. start date not empty
        // 2. end date not smaller than start date
        $('#updateParticipants').on('submit', function(e){

            for(var i = 1; i <= countRows; i++ ){

                if($('#start-date'+i).val() == ''){

                   $('#start-date'+i).addClass('error-border').after('<p class="error-message" id="error-start-date' + i + '" ><?php echo xlt('is not valid');?></p>');
                   $('#start-date'+i).on('focus', function(){
                        $(this).removeClass('error-border');
                        $('#error-start-date' + i).hide();
                   });
                   return  e.preventDefault();
                }
                if(typeof $('#end-date'+i).val() == 'string'){
                    if(moment($('#end-date'+i).val()).isBefore($('#start-date'+i).val())){
                        $('#end-date'+i).addClass('error-border').after('<p class="error-message" id="error-end-date' + i + '" ><?php echo xlt('End date must be equal or bigger than start date');?></p>');
                        $('#end-date'+i).on('focus', function(){
                            $(this).removeClass('error-border');
                            $('#error-end-date' + i).hide();
                        });
                        return  e.preventDefault();
                    }
                }
            }
        });

        $('#saveForm').on('click', function () {
            top.restoreSession();
            $('#updateParticipants').append('<input type="hidden" name="save">').submit();
        });

        $('#addParticipant').on('click', function(e){
            e.preventDefault();
           if(!$('#add-participant-form').hasClass('showAddForm')){
               $('#add-participant-form').addClass('showAddForm');
           } else {
               $('#add-participant-form').removeClass('showAddForm');
           }
        });
        $('#cancelAddParticipant').on('click', function(e){
            e.preventDefault();
            $('#add-participant-form').removeClass('showAddForm');
        });

        $('#participant_name').on('click', function(){
            top.restoreSession();
            var url = '<?php echo $GLOBALS['webroot']?>/interface/main/calendar/find_patient_popup.php';
            dlgopen(url, '_blank', 500, 400);
        });

    });

    function setpatient(pid, lname, fname, dob){

        $('#pid').val(pid);
        $('#participant_name').val(fname + " " + lname);
    }

    function refreshme() {
        top.restoreSession();
        location.href = "<?php echo $GLOBALS['webroot'] . '/interface/therapy_groups/index.php?method=groupParticipants&group_id='. attr($groupId) ?>";
    }

    function newGroup(){
        <?php if ($GLOBALS['new_tabs_layout']) : ?>
        top.restoreSession();
        parent.left_nav.loadFrame('gcv4','enc','forms/newGroupEncounter/new.php?autoloaded=1&calenc=')
        <?php else : ?>
        top.restoreSession();
        top.frames['RBot'].location = '<?php echo $GLOBALS['web_root'] . "/interface/" ?>' + 'forms/newGroupEncounter/new.php?autoloaded=1&calenc=';
        <?php endif; ?>
    }
   // parent.left_nav.setTherapyGroup(<?php echo attr($group_id);?>,'<?php echo 'test'?>');
    /* show the encounters menu in the title menu (code like interface/forms/newGroupEncounter/save.php) */
    <?php
    $result4 = sqlStatement("SELECT fe.encounter,fe.date,openemr_postcalendar_categories.pc_catname FROM form_groups_encounter AS fe ".
        " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.group_id = ? order by fe.date desc", array($groupId));
    ?>

    EncounterDateArray=new Array;
    CalendarCategoryArray=new Array;
    EncounterIdArray=new Array;
    Count=0;
    <?php
    if(sqlNumRows($result4)>0)
    while($rowresult4 = sqlFetchArray($result4))
    {
    ?>
    EncounterIdArray[Count]='<?php echo attr($rowresult4['encounter']); ?>';
    EncounterDateArray[Count]='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date'])))); ?>';
    CalendarCategoryArray[Count]='<?php echo attr(xl_appt_category($rowresult4['pc_catname'])); ?>';
    Count++;
    <?php
    }
    ?>
    top.window.parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);

</script>
<?php    $use_validate_js = 1;?>
<?php validateUsingPageRules($_SERVER['PHP_SELF'] . '?method=groupParticipants');?>
<script src="<?php echo $GLOBALS['webroot']?>/library/dialog.js"></script>
<?php require 'footer.php'; ?>

