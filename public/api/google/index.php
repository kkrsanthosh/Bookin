<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Places API - All Place Data Fields</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 900px;">
            <div class="card-header text-center bg-primary text-white">
                <h2>Get Google Place Details from Website</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="website" class="form-label">Enter Website (Start with "www.")</label>
                        <input type="text" class="form-control" id="website" name="website" placeholder="www.starbucks.com" value="<?= isset($_GET['url']) ? htmlspecialchars($_GET['url']) : '' ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Get Details</button>
                    </div>
                </form>

                <?php
                $apiKey = "AIzaSyA7MiQJIRLnrnyZnqTuh3fnIcuh2yhPQhM"; // Replace with your Google Places API key
                $website = "";
                $jsonFolder = "json_files";

                if (!file_exists($jsonFolder)) {
                    mkdir($jsonFolder, 0777, true);
                }

                if (isset($_GET["url"])) {
                    $website = trim($_GET["url"]);
                } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $website = trim($_POST["website"]);
                }

                if (!empty($website)) {
                    if (strpos($website, "www.") !== 0) {
                        echo '<div class="alert alert-danger mt-3">The website URL must start with "www.".</div>';
                    } else {
                        $filename = $jsonFolder . '/' . str_replace("www.", "", $website) . '.json';

                        if (file_exists($filename)) {
                            $detailsData = json_decode(file_get_contents($filename), true);
                            echo '<div class="alert alert-info mt-3 text-center">Loaded from saved file: ' . basename($filename) . '</div>';
                        } else {
                            $findPlaceUrl = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=" . urlencode($website) . "&inputtype=textquery&fields=place_id&key=" . $apiKey;
                            $findPlaceResponse = file_get_contents($findPlaceUrl);
                            $findPlaceData = json_decode($findPlaceResponse, true);

                            if ($findPlaceData && isset($findPlaceData["candidates"][0]["place_id"])) {
                                $placeId = htmlspecialchars($findPlaceData["candidates"][0]["place_id"]);

                                $detailsUrl = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeId}&fields=address_component,formatted_address,geometry,icon,name,photos,place_id,plus_code,rating,reviews,types,url,user_ratings_total,website&key={$apiKey}";
                                $detailsResponse = file_get_contents($detailsUrl);
                                $detailsData = json_decode($detailsResponse, true);

                                $detailsData["result"]["place_id"] = $placeId;
                                file_put_contents($filename, json_encode($detailsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                                echo '<div class="alert alert-success mt-3 text-center">JSON file saved: ' . basename($filename) . '</div>';
                            } else {
                                echo '<div class="alert alert-warning mt-3 text-center">No Place ID found for this website.</div>';
                                $detailsData = null;
                            }
                        }

                        if ($detailsData && isset($detailsData["result"])) {
                            $result = $detailsData["result"];
                            $placeId = htmlspecialchars($result["place_id"] ?? "N/A");
                            $reviews = $result["reviews"] ?? [];
                            $photos = $result["photos"] ?? [];
							
                            echo '<h3 class="mt-4 mb-3">Place Information:</h3>';
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-bordered table-striped">';
                            echo '<tbody>';
                            foreach ($result as $key => $value) {
                                echo '<tr><td><strong>' . htmlspecialchars($key) . '</strong></td><td>' . htmlspecialchars(json_encode($value, JSON_UNESCAPED_UNICODE)) . '</td></tr>';
                            }
                            echo '</tbody></table></div>';							

                            echo '<div class="accordion mt-4" id="placeAccordion">';
                            echo '<div class="accordion-item">';
                            echo '<h2 class="accordion-header" id="headingReviews">';
                            echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="true" aria-controls="collapseReviews">User Reviews</button>';
                            echo '</h2>';
                            echo '<div id="collapseReviews" class="accordion-collapse collapse show" aria-labelledby="headingReviews">';
                            echo '<div class="accordion-body">';

                            if (!empty($reviews)) {
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-bordered table-striped">';
                                echo '<thead class="table-dark"><tr><th>Profile</th><th>Author</th><th>Rating</th><th>Review</th><th>Time</th></tr></thead>';
                                echo '<tbody>';
                                $count = 0;
                                foreach ($reviews as $review) {
                                    if ($count >= 10) break;
                                    $profilePhoto = $review["profile_photo_url"] ?? "https://via.placeholder.com/50";
                                    echo '<tr>';
                                    echo '<td><img src="' . htmlspecialchars($profilePhoto) . '" alt="Profile Photo" width="50" height="50" class="rounded-circle"></td>';
                                    echo '<td>' . htmlspecialchars($review["author_name"] ?? "N/A") . '</td>';
                                    echo '<td>' . htmlspecialchars($review["rating"] ?? "N/A") . '</td>';
                                    echo '<td>' . htmlspecialchars($review["text"] ?? "N/A") . '</td>';
                                    echo '<td>' . htmlspecialchars(date("Y-m-d H:i:s", $review["time"] ?? 0)) . '</td>';
                                    echo '</tr>';
                                    $count++;
                                }
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning text-center">No reviews available.</div>';
                            }

                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            echo '<div class="accordion-item">';
                            echo '<h2 class="accordion-header" id="headingPhotos">';
                            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePhotos" aria-expanded="false" aria-controls="collapsePhotos">Business Photos</button>';
                            echo '</h2>';
                            echo '<div id="collapsePhotos" class="accordion-collapse collapse" aria-labelledby="headingPhotos">';
                            echo '<div class="accordion-body">';

                            if (!empty($photos)) {
                                echo '<div class="row">';
                                foreach ($photos as $photo) {
                                    $photoReference = $photo["photo_reference"];
                                    $photoUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference={$photoReference}&key={$apiKey}";
                                    echo '<div class="col-md-4 mb-3">';
                                    echo '<img src="' . htmlspecialchars($photoUrl) . '" alt="Business Photo" class="img-fluid rounded">';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning text-center">No photos available.</div>';
                            }

                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                            echo '<h3 class="mt-4 mb-3">JSON Response:</h3>';
                            echo '<pre class="bg-light p-3 border" style="max-height: 600px; overflow:auto;">' . htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>';
                        } else {
                            echo '<div class="alert alert-warning mt-3 text-center">No details found for this place.</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
