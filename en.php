<?php
?>
<!DOCTYPE html>
<html>
<?php
echo "<link rel='stylesheet' href='" . plugin_dir_url(__FILE__) . "css/style.css'>";
?>

​

<head>
    <title>TEXT GENERATOR</title>
</head>
​

<body>
    <br>
    <div>
        <div class="box1">
            <div class="box1_inner">
                <div>
                    <h1 style="margin-left: 30px; margin-top: 30px;">Text Generator</h1><br>
                    <form method="post">
                        <label for="theme">Title:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion1'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup">Enter the title for your<br>article to be published. </div>
                        <input type="text" name="theme" id="theme" placeholder="Enter the title here">
                        <br><br>
                        <label for="keyword">Keyword:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion2'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup2">Enter you article keyword<br>for SEO optimization. </div>
                        <input type="text" name="keyword" id="keyword" placeholder="Enter your keyword here">
                        <br><br>
                        <label for="word_count">Number of Words:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion3'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup3">Enter the desired number of<br>words between 1- 400. </div>
                        <input type="number" name="word_count" id="word_count" min="1" max="4000" placeholder="Enter the number of words here">
                        <br><br>
                        <input type="radio" id="publish" name="fav_language" value="publish">
                        <label for="html" class="artigog">publish</label>
                        <input type="radio" id="draft" name="fav_language" value="draft">
                        <label for="css">draft</label><br>
                        <br><br>
                        <label for="api">API:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion4'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup4">Enter your API. If you don´t have an<br>API yet, get it here.</div>
                        <input type="text" name="api" id="api" placeholder="Enter your API here">
                        <br><br>
                        <button class="button1" id="generate-btn" type="submit">Generate</button>
                        <div class="loading" id="loading">
                            <div class="loader"></div>
                            <div class="message">Wait a second while the machine follow your steps... <br> Till one day</div>
                        </div>
                        <script>
                            document.getElementById('generate-btn').addEventListener('click', function() {
                                // Show the loading spinner
                                document.getElementById('loading').style.display = 'block';
                            });
                        </script>
                    </form>
                </div>
                <div id="generated_text"></div>
            </div>
        </div>
        <div class="box2">
            <div class="box2_inner">
                <div class="topright">
                    <label for="cars" style="display: block;" class="elang">Choose Language:</label>
                    <?php
                    echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion5'>";
                    ?>
                    <!-- Popup -->
                    <div class="popup5">Select the language in which you want to generate the article. </div>
                    <form method="POST" id="language-form">
                        <select name="language" id="language" style="display: block; width:190px; height:30px" class="lang">
                            <option value="en">English</option>
                            <option value="pt">Portugues</option>
                            <option value="fr">Français</option>
                        </select>
                        <br><br>
                        <button class="button2" type="change" id="change" name="change">Change</button>
                        <label for="cars" style="display: block;" class="elang">Tone of Voice:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion6'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup6">Select the tone of voice to be used in the article's narrative. </div>
                        <select name="voice" id="voice" style="display: block; width:190px; height:30px" class="lang">
                            <option value="formal">Formal</option>
                            <option value="informal">Informal</option>
                            <option value="persuasive">Persuasive</option>
                        </select>
                    </form>
                    <script>
                        document.getElementById('change').addEventListener('click', function() {
                            document.getElementById('language-form').submit();
                        });
                    </script>
                    <br>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php
//$language = sanitize_text_field($_POST['language']);

// Redireciona para a página correspondente, com base no valor do "select"
if ($language === 'en') {
    wp_redirect(admin_url('admin.php?page=en.php'));
    exit;
} else if ($language === 'pt') {
    wp_redirect(admin_url('admin.php?page=pt.php'));
    exit;
} else if ($language === 'fr') {
    wp_redirect(admin_url('admin.php?page=fr.php'));
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme']) && isset($_POST['word_count']) && isset($_POST['keyword']) && isset($_POST['api'])) {


    $voice = $_POST['voice'];
    $openai_api_key = $_POST['api'];
    $openai_api_endpoint = 'https://api.openai.com/v1/chat/completions';


    $data = array(
        'model' => 'gpt-3.5-turbo',
        'prompt' => 'Write a text about ' . $_POST['theme'] . ' in ' . $_POST['word_count'] . ' words with a keyword ' . $_POST['keyword'] . 'as  ' . $_POST['voice'] . ' voice:',
        'max_tokens' => 4000,
        'n' => 1,
        'temperature' => 0.8,
        'top_p' => 1,
        'stream' => false,
        'logprobs' => null,
        'stop' => '\n'
    );

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openai_api_key
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $openai_api_endpoint);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    var_dump($response);

    curl_close($ch);

    $response_data = json_decode($response, true);

    if (!empty($response_data['choices'][0]['text'])) {
        $generated_text = $response_data['choices'][0]['text'];
        $selected_value = $_POST["fav_language"];
        if ($selected_value == "publish") {
            $post_data = array(
                'post_title'    => $_POST['theme'],
                'post_content'  => $generated_text,
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_category' => array(1),
                'post_type' => 'post'
            );
            $post_id = wp_insert_post($post_data);
        } if($selected_value == "draft") {
            $post_data = array(
                'post_title'    => $_POST['theme'],
                'post_content'  => $generated_text,
                'post_status'   => 'draft',
                'post_author'   => 1,
                'post_category' => array(1),
                'post_type' => 'post'
            );
            $post_id = wp_insert_post($post_data);
        }
    } else {
        $generated_text = 'Sorry, I was not able to generate a text about ' . $_POST['theme'] . '.';
    }
    echo "<script>document.getElementById('loading-spinner').style.display = 'none';</script>";
    echo '<span style="font-family: Open Sans; font-weight: bold;margin-top:16px;padding-top: 5px; margin-left:80px;margin-bottom:10px; font-size:16px;">Title: </span>' . $_POST['theme'];
    echo '<br>';
    echo '<span style="font-family: Open Sans; font-weight: bold;margin-top:10px;  margin-left:80px; font-size:16px;">Words: </span>' . str_word_count($generated_text);
    echo '<textarea rows="20" cols="20" style="width:89%;resize:none;margin-top:20px;margin-bottom:20px; margin-left:80px;padding:20px; border-radius:5px; border-color: #D9D9D9; background-color:#D9D9D9; font-family: \'Open Sans\', sans-serif;">';
    echo $generated_text;
    echo '</textarea>';
}
?>