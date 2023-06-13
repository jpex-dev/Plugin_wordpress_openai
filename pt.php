<?php
?>
<!DOCTYPE html>
<html>

<?php
echo "<link rel='stylesheet' href='" . plugin_dir_url(__FILE__) . "css/style.css'>";
?>

<head>
    <title>GERADOR DE TEXTO</title>
</head>

<body>
    <br>
    <div>
        <div class="box1">
            <div class="box1_inner">
                <div>
                    <h1 style="margin-left: 30px; margin-top: 30px;">Gerador de texto</h1><br>
                    <form method="post">
                        <label for="theme">Titulo:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion1'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup">Insira o título para o<br>seu artigo a publicar. </div>
                        <input type="text" name="theme" id="theme" placeholder="Insira o título aqui">
                        <br><br>
                        <label for="keyword">Palavra Chave:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion2'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup2">Insira as palavras-chave do seu<br>artigo para otimização de SEO. </div>
                        <input type="text" name="keyword" id="keyword" placeholder="Insira a sua keyword aqui">
                        <br><br>
                        <label for="word_count">Número de palavras:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion3'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup3">Insira o número de palavras<br>pretendido entre 1- 400. </div>
                        <input type="number" name="word_count" id="word_count" min="1" max="4000" placeholder="Insira o número de palavras aqui">
                        <br><br>
                        <input type="radio" id="publish" name="fav_language" value="publish">
                        <label for="html" class="artigog">publicar</label>
                        <input type="radio" id="draft" name="fav_language" value="draft">
                        <label for="css">rascunho</label><br>
                        <br><br>
                        <label for="api">API:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion4'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup4">Insira a sua API. Caso ainda não<br>possua uma API obtenha aqui.</div>
                        <input type="text" name="api" id="api" placeholder="Insira a sua API aqui">
                        <br><br>
                        <button class="button1" id="generate-btn" type="submit">Gerar</button>
                        <div class="loading" id="loading">
                            <div class="loader"></div>
                            <div class="message1">Espere um segundo enquanto a máquina segue seus passos... <br> Até um dia</div>
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

                    <label for="cars" style="display: block;" class="elang">Escolha linguagem:</label>
                    <?php
                    echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion5'>";
                    ?>
                    <!-- Popup -->
                    <div class="popup5">Selecione o idioma em que pretende gerar o artigo. </div>
                    <form method="POST" id="language-form">
                        <select name="language" id="language" style="display: block; width:190px; height:30px" class="lang">
                            <option value="pt">Portugues</option>
                            <option value="en">English</option>
                            <option value="fr">Français</option>
                        </select>
                        <br><br>
                        <button class="button2" type="change" id="change" name="change">Mudar</button>
                        <label for="cars" style="display: block;" class="elang">Tom de Voz:</label>
                        <?php
                        echo "<img src='" . plugin_dir_url(__FILE__) . "images/questionmark.png' width='12' height='12' class='imgquestion6'>";
                        ?>
                        <!-- Popup -->
                        <div class="popup6">Selecione o tom de voz a ser utilizado na narrativa do artigo. </div>
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
$language = sanitize_text_field($_POST['language']);

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


    $openai_api_key = $_POST['api'];
    $openai_api_endpoint = 'https://api.openai.com/v1/chat/completions';


    $data = array(
        'model' => 'gpt-3.5-turbo',
        'prompt' => 'Escreva um texto sobre ' . $_POST['theme'] . ' em ' . $_POST['word_count'] . ' palavras com a keyword ' . $_POST['keyword'] . ' em Português de Portugal:',
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
        $generated_text = 'DEsculpe não consegui gerar um texto sobre ' . $_POST['theme'] . '.';
    }
    echo "<script>document.getElementById('loading-spinner').style.display = 'none';</script>";
    echo '<span style="font-family: Open Sans; font-weight: bold;margin-top:6px;padding-top: 5px; margin-left:80px;margin-bottom:10px; font-size:16px;">Título: </span>' . $_POST['theme'];
    echo '<br>';
    echo '<span style="font-family: Open Sans; font-weight: bold;margin-top:10px;  margin-left:80px;font-size:16px;">Palavras: </span>' . str_word_count($generated_text);
    echo '<textarea rows="20" cols="20" style="width:89%;resize:none;margin-top:20px;margin-bottom:20px; margin-left:80px;padding:20px; border-radius:5px; border-color: #D9D9D9; background-color:#D9D9D9; font-family: \'Open Sans\', sans-serif;" >';
    echo $generated_text;
    echo '</textarea>';
}
?>