<?php 
// 這支程式就是將自己模擬成 client 端,
// 發送 POST 給 Google GCM server
// 並在 Google GCM server 回傳某 Android 裝置已移除您的 app 時
// 做對應的處理
 
$apiKey = "AIzaSyDtEqPzGDeH2St1rc9XpWUhRvcfbkWMr8A";
 
// 列出要發送的 user 端 Android 裝置
$sql = "SELECT * FROM gcm_client_table";
$rs = $pdo->query($sql);
 
$recnt = count($rs);
  
// 總記錄數/每批發送數, 若不能整除, 則發送訊息迴圈數 +1
$SendMax = 1000;     // 每批發送訊息給 Android 裝置數
$SendLoop = ceil($recnt/$SendMax);
 
$pkey = -1;
for($x=0;$x<$SendLoop;$x++)
{
    $aRegID = array();
     
    for($y=0;$y<$SendMax;$y++)
    {
        $index = ($x*$SendMax) + $y;
        if($index<$recnt)
            {
                $row = $rs[$index];
                array_push($aRegID, $row['gcm_id']);
            }
        else
            {
                break;
            }
    }
         
    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';
 
    // 要發送的訊息內容
    // 例如我要發送 message, campaigndate, title, description 四樣資訊
    // 就將這 4 個組成陣列
    // 您可依您自己的需求修改
    $fields = array('registration_ids'  => $aRegID,
                    'data'              => array( 'message' => $message,
                                                    'campaigndate' => $campaigndate,
                                                    'title' => $title,
                                                    'description' => $description
                                                  )
                );
 
    $headers = array('Content-Type: application/json',
                     'Authorization: key='.$apiKey
                    );
 
    // Open connection
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
    // 送出 post, 並接收回應, 存入 $result
    $result = curl_exec($ch);
     
    // 由回傳結果, 取得已解除安裝的 regID
    // 自資料庫中刪除
    $aGCMresult = json_decode($result,true);
    $aUnregID = $aGCMresult['results'];
    $unregcnt = count($aUnregID);
    for($i=0;$i<$unregcnt;$i++)
    {
        $aErr = $aUnregID[$i];
        if($aErr['error']=='NotRegistered')
        {
            $sqlTodel = "DELETE FROM gcmclient
                             WHERE gcm_id='".$aRegID[$i]."' ";
            $pdo->query($sqlTodel);
        }
    }
 
    // Close connection
    curl_close($ch);
    // GCM end -------
    unset($aRegID);
}

?>