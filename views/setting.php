<div class="wrap">
  <h2><?php echo(__('Setting Item Titles', 'easy-attendance'))?></h2>
  <form method="post" action="options.php" enctype="multipart/form-data" encoding="multipart/form-data">
    <?php
    settings_fields($__option);
    do_settings_sections($__option); ?>
    <div class="metabox-holder">
      <div class="postbox ">
        <h3 class='hndle'><span>name</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of Name', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('name') ?></p>
          </div>
        </div>
      </div>

      <div class="postbox ">
        <h3 class='hndle'><span>date</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of Date', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('date') ?></p>
          </div>
        </div>
      </div>

      <div class="postbox ">
        <h3 class='hndle'><span>time_start</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of Start Time', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('time_start') ?></p>
          </div>
        </div>
      </div>

      <div class="postbox ">
        <h3 class='hndle'><span>time_end</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of End Time', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('time_end') ?></p>
          </div>
        </div>
      </div>

      <div class="postbox ">
        <h3 class='hndle'><span>time</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of Time', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('time') ?></p>
          </div>
        </div>
      </div>

      <div class="postbox ">
        <h3 class='hndle'><span>memo</span></h3>
        <div class="inside">
          <div class="main">
            <p class="setting_description"><?php echo(__('Explanation of Memo', 'easy-attendance'))?></p>
            <p><?php self::echoInputText('memo') ?></p>
          </div>
        </div>
      </div>

    </div>
    <?php submit_button(); ?>
  </form>
</div>
