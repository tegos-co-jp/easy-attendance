    <p><?php self::echoInputSelect( 'name', $namelist, true ) ?></p>
    <p><?php self::echoInputText( 'date', 10, true ,date('Y-m-d'), 'date' ) ?></p>
    <p><?php self::echoInputText( 'time_start', 10, true , '09:00', 'time' ) ?></p>
    <p><?php self::echoInputText( 'time_end', 10, true , '17:30', 'time' ) ?></p>
    <p><?php self::echoInputText( 'time', 10, true , '07:30', 'time' ) ?></p>
    <p><?php self::echoInputTextArea( 'memo', 10, 60 ) ?></p>
