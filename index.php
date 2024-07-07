<?php

class Ares {
    private const BASE_URL = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/';

    private function valide_ico($ico) {
        if (strlen($ico) != 8) {
            return false;
        }

        for ($i = 0; $i < 8; $i++) {
            if (!is_numeric($ico[$i])) {
                return false;
            }
        }

        return true;
    }

    public function get_company_data($ico) {
        if (!$this->valide_ico($ico)) {
            return null;
        }

        $url = self::BASE_URL . $ico;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);

        curl_close($curl);

        if ($curl_error) {
            echo "Error cURL: " . $curl_error;
            return null;
        }

        if ($http_status != 200) {
            echo "HTTP error: " . $http_status;
            return null;
        }

        $data = json_decode($response, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            echo "Error while JSON decoding: " . json_last_error_msg();
            return null;
        }

        return $data;
    }

    public function beautify_json($data) {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARES Company Info</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mt-5">ARES Company Info</h1>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="icoInput">Enter IČO:</label>
            <input type="text" class="form-control" id="icoInput" name="ico" placeholder="Enter 8-digit IČO" required>
        </div>
        <button type="submit" class="btn btn-primary">Get Company Info</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ico = $_POST['ico'];

        $ares = new Ares();

        $companyData = $ares->get_company_data($ico);

        if ($companyData === null) {
            echo '<div class="alert alert-danger mt-3">Error: Company data was not found</div>';
        } else {
            echo '<div class="result mt-3">';
            echo '<h2>Company Info:</h2>';
            echo '<pre>' . $ares->beautify_json($companyData) . '</pre>';
            echo '</div>';
        }
    }
    ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
