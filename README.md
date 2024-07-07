Ares Class
Constants and Methods:
private const BASE_URL: Defines the base URL for the ARES registry API where requests are sent to fetch company data.
private function valide_ico($ico): Validates the format of the Identification Number (IČO). It checks that the IČO consists of exactly 8 digits and contains only numeric characters.
public function get_company_data($ico): Retrieves company data based on the provided IČO. It validates the IČO, sends a request to the ARES API using cURL, handles HTTP and cURL errors, parses the JSON response, and returns the data as an associative array.
public function beautify_json($data): Formats the given data into a human-readable JSON string with indentation and without Unicode escaping.
HTML Page
The webpage contains a form where users input an 8-digit IČO.
Upon form submission via POST method, PHP code processes the entered IČO.
It creates an instance of Ares, calls get_company_data to fetch company information based on the entered IČO.
If data retrieval is successful, it displays the company information in a formatted JSON block with the class result using beautify_json.
In case of errors (invalid IČO format, HTTP request error, JSON decoding errors), appropriate error messages are displayed.
Summary
This application allows users to input an IČO to retrieve and display company information from the ARES database. It utilizes PHP for server-side processing, cURL for making API requests, and Bootstrap for styling the HTML form and result display. The Ares class encapsulates the logic for validating the IČO, fetching data from the ARES API, and formatting the retrieved data into a readable JSON format for display on the webpage.
