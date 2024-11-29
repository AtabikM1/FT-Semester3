<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Post a New Job</h1>
            <form id="postJobForm" class="space-y-6">
                <div>
                    <label for="judul" class="block text-lg font-medium text-gray-700">Job Title</label>
                    <input type="text" name="judul" id="judul" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="deskripsi" class="block text-lg font-medium text-gray-700">Job Description</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div>
                    <label for="tipe_loker" class="block text-lg font-medium text-gray-700">Job Type</label>
                    <select name="tipe_loker" id="tipe_loker" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Magang">Magang</option>
                    </select>
                </div>

                <div>
                    <label for="lokasi" class="block text-lg font-medium text-gray-700">Location</label>
                    <input type="text" name="lokasi" id="lokasi" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="gaji" class="block text-lg font-medium text-gray-700">Salary</label>
                    <input type="text" name="gaji" id="gaji"
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="tanggal_deadline" class="block text-lg font-medium text-gray-700">Deadline</label>
                    <input type="date" name="tanggal_deadline" id="tanggal_deadline" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">Post
                        Job</button>
                </div>
            </form>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 shadow-md">
            <h2 class="text-2xl font-bold text-green-600 mb-4">Success!</h2>
            <p id="successMessage"></p>
            <button onclick="closeModal()"
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Close</button>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }

        $(document).ready(function () {
            $('#postJobForm').submit(function (event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'post-job.php',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#successMessage').text(response.message);
                            $('#successModal').show();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function () {
                        alert('An error occurred while posting the job.');
                    }
                });
            });
        });
    </script>
</body>

</html>