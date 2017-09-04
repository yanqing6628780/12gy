<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 姓名</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[name]" placeholder="姓名" datatype="*"  value="<?=isset($profile->name) ? $profile->name : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label">籍贯</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[native_place]" placeholder="籍贯" value="<?=isset($profile->native_place) ? $profile->native_place : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 性别</label>
    <div class="col-sm-3 input-box">
    <label class="radio-inline">
    <input <?=isset($profile->sex) ? radio_check(1, $profile->sex)  : "checked"?> type="radio" name="profile[sex]" value="1"> 男
    </label>
    <label class="radio-inline">
    <input <?=isset($profile->sex) ? radio_check(0, $profile->sex)  : ""?>  type="radio" name="profile[sex]" value="0"> 女
    </label>
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label"><span class="require">*</span> 身份证</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[id_card]" placeholder="身份证" datatype='idcard'  nullmsg="请填写身份证号码！" errormsg="您填写的身份证号码不对！"  value="<?=isset($profile->id_card) ? $profile->id_card : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 出生年月日</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control datepick" name="profile[birthday]" placeholder="1990-01-01" value="<?=isset($profile->birthday) ? $profile->birthday : "1990-01-01"?>" readonly>
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label">文化程度</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[ed]" placeholder="文化程度" value="<?=isset($profile->ed) ? $profile->ed : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">QQ</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[qq]" placeholder="" value="<?=isset($profile->qq) ? $profile->qq : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label"><span class="require">*</span> 手机</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[phone]" placeholder="手机" datatype="m"   value="<?=isset($profile->phone) ? $profile->phone : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 居住社区</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[community]" placeholder="居住社区" datatype="*"  value="<?=isset($profile->community) ? $profile->community : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label">E-mail</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[email]" placeholder="E-mail" value="<?=isset($profile->email) ? $profile->email : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">兴趣</label>
    <div class="col-sm-3 input-box">
    <input type="text" class="form-control" name="profile[hobby]" placeholder="兴趣" value="<?=isset($profile->hobby) ? $profile->hobby : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
    <label class="col-sm-2 control-label">流利语言</label>
    <div class="col-sm-3 input-box checkbox">
    <?php foreach ($my_language as $key => $item): ?>
    <label>

    <input value="<?=$item?>" type="checkbox" name="profile[my_language][<?=$key?>]" <?=isset($profile->my_language) && $profile->my_language->$key->status ? "checked" : ""?>><?=$item?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 地址</label>
    <div class="col-sm-8 input-box">
    <input type="text" class="form-control" name="profile[address]" placeholder="地址" datatype="*"  value="<?=isset($profile->address) ? $profile->address : ""?>">
    <span aria-hidden="true" class="glyphicon form-control-feedback"></span>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><span class="require">*</span> 工作现状</label>
    <div class="col-sm-8 input-box">
    <?php foreach ($work_status as $key => $item): ?>
    <label class="radio-inline">
    <input <?=isset($profile->work_status) ? radio_check($key, $profile->work_status)  : ""?> value="<?=$key?>" type="radio" name="profile[work_status]" datatype="*"><?=$item?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<h4 class="text-center">想加入的义工服务计划</h4>
<div class="form-group ">
<label class="col-sm-2 control-label"><span class="require">*</span> 义工储备库</label>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">医疗保健</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($health_care as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[health_care][<?=$key?>]" <?=isset($profile->health_care) && $profile->health_care[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[health_care][<?=$key?>]" value="<?=isset($profile->health_care) && $profile->health_care[$key]->status ? $profile->health_care[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">电脑技术</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($computer_tec as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[computer_tec][<?=$key?>]" <?=isset($profile->computer_tec) && $profile->computer_tec[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[computer_tec][<?=$key?>]" value="<?=isset($profile->computer_tec) && $profile->computer_tec[$key]->status ? $profile->computer_tec[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">手工制作</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($handmade as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[handmade][<?=$key?>]" <?=isset($profile->handmade) && $profile->handmade[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[handmade][<?=$key?>]" value="<?=isset($profile->handmade) && $profile->handmade[$key]->status ? $profile->handmade[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">个人才艺</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($personal_talent as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[personal_talent][<?=$key?>]" <?=isset($profile->personal_talent) && $profile->personal_talent[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[personal_talent][<?=$key?>]" value="<?=isset($profile->personal_talent) && $profile->personal_talent[$key]->status ? $profile->personal_talent[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">运动教学</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($sports_teach as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[sports_teach][<?=$key?>]" <?=isset($profile->sports_teach) && $profile->sports_teach[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[sports_teach][<?=$key?>]" value="<?=isset($profile->sports_teach) && $profile->sports_teach[$key]->status ? $profile->sports_teach[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">其他领域</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($other_areas as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[other_areas][<?=$key?>]" <?=isset($profile->other_areas) && $profile->other_areas[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[other_areas][<?=$key?>]" value="<?=isset($profile->other_areas) && $profile->other_areas[$key]->status ? $profile->other_areas[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">专业维修</label>
    <div class="col-sm-8 input-box checkbox">
    <?php foreach ($pro_repair as $key => $item): ?>
    <?php 
    $arr = explode(',',$item);
    $name = $arr[0];
    ?>
    <label>
    <input value="<?=$name?>" type="checkbox" name="profile[pro_repair][<?=$key?>]" <?=isset($profile->pro_repair) && $profile->pro_repair[$key]->status ? "checked" : ""?>><?=$name?>
    <?php if ( isset($arr[1]) ): ?>
    <input class="checkbox_input" placeholder="请填写" type="text" name="field_content[pro_repair][<?=$key?>]" value="<?=isset($profile->pro_repair) && $profile->pro_repair[$key]->status ? $profile->pro_repair[$key]->content : ""?>">
    <?php endif ?>
    </label>
    <?php endforeach ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label">义工服务项目<br>(最多选择八个)</label>
    <div class="col-sm-8 input-box checkbox">
        <select class="form-control" id="serviceteam_category">
            <?php foreach ($serviceteam_category as $key => $category): ?>
            <option value=".category_<?=$category['id']?>"><?=$category['name']?></option>
            <?php endforeach ?>
        </select>
        <?php foreach ($serviceteam as $key => $team): ?>
        <label class="category_<?=$team['category_id']?> team_checkbox" style="display: none">
        <input class="serviceteam" name="profile[service_items][]" type="checkbox" value="<?=$team['id']?>" <?php if(isset($profile->pro_repair)){ echo in_array($team['id'], $profile->service_items) ? 'checked' : '';} ?> > <?=$team['name']?>
        </label>
        <?php endforeach; ?>
    </div>
</div>
<div class="form-group ">
    <label class="col-sm-2 control-label"><sapn class="require">*</sapn> 可参与<br>服务时间</label>
    <div class="col-sm-8 input-box">
        <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th class="text-center">时段</th>
                    <th class="text-center">平时</th>
                    <th class="text-center">周六</th>
                    <th class="text-center">周日</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($times as $key => $item): ?> 
                <tr class="text-center">
                    <td><?=$item?></td>
                    <?php foreach ($days as $value): ?> 
                    <td>
                        <input class="service_times" <?=isset($profile->$value) && $profile->{$value}[$key] ? "checked" : ""?> value="1" type="checkbox" name="profile[<?=$value?>][<?=$key?>]">
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>