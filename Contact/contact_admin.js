window.onload = function() {
    // Fetch contact form submissions from the database using PHP (e.g., via AJAX)
    fetchSubmissions();
};

function fetchSubmissions() {
    // Dummy data for demonstration purposes (replace with actual database query)
    var submissions = [
        { name: 'John Doe', phoneNumber: '123-456-7890', message: 'Hello, how can I help you?' },
        { name: 'Jane Smith', phoneNumber: '987-654-3210', message: 'I have a question about your products.' },
        // Add more submissions as needed
    ];

    // Populate table with submissions
    var tableBody = document.querySelector('#submissions-table tbody');
    submissions.forEach(function(submission) {
        var row = document.createElement('tr');
        row.innerHTML = `
            <td>${submission.name}</td>
            <td>${submission.phoneNumber}</td>
            <td>${submission.message}</td>
        `;
        tableBody.appendChild(row);
    });
}
