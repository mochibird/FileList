<?php
$watchs = [];
$command = [];
while(true){
if($command === '1'){
    echo 'テレビのチャンネル番号を入力してください' . PHP_EOL;
    $channel = trim(fgets(STDIN)) . 'ch';
    echo 'テレビのチャンネルの視聴分数(整数)を入力してください' . PHP_EOL;
    $minute = (int) trim(fgets(STDIN));
    $watch = [
        'channel' => $channel,
        'minute' => $minute,
    ];
    $watchs[] = $watch;
}elseif($command === '2'){
        echo '登録内容を確認します' . PHP_EOL;
        $watchMinutes = array_column($watchs, 'minute');
        $watchTotal = round(array_sum($watchMinutes) / 60);
        echo $watchTotal . '時間' . PHP_EOL;
        $watchChannels = array_column($watchs, 'channel');
        $watchCounts = array_count_values($watchChannels);
        foreach($watchs as $watch){
            echo $watch['channel'] . ' ' . $watch['minute'] . ' ' . $watchCounts[$watch['channel']] . PHP_EOL;
        }
        echo '---------------------------' . PHP_EOL . PHP_EOL;
}elseif($command === 'stop'){
    break;
}
    echo 'テレビの視聴時間を管理します' . PHP_EOL;
    echo '1:テレビの視聴時間を記録します' . PHP_EOL;
    echo '2:テレビの視聴時間の登録内容を確認します' . PHP_EOL;
    echo 'stop:アプリを終了します' . PHP_EOL;
    echo 'コマンドを選択してください(1・2・stop)';
    $command = trim(fgets(STDIN));
}
