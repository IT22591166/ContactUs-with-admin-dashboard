<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=">
  <title>Responsive Contact Us Page</title>
  <script src="https://kit.fontawesome.com/c32adfdcda.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="Contact.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <section>
    <div class="section-header">
      <div class="container">
        <h2>Contact Us</h2>
        <p>Make an inquiry</p>
      </div>
    </div>
    
    <div class="container">
      <div class="row">
        
        <div class="contact-info">
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <i class="fas fa-home"></i>
            </div>
            <div class="contact-info-content">
              <h4>Address</h4>
              <p>PO BOX 53, Nungamugoda,<br/>  Kelaniya,  <br/> Sri Lanka </p>
            </div>
          </div>
          
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <i class="fas fa-phone"></i>
            </div>
            <div class="contact-info-content">
              <h4>Phone</h4>
              <p>011 778 0380</p>
              <p>011 482 2800</p>
            </div>
          </div>
          
          <div class="contact-info-item">
            <div class="contact-info-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-info-content">
              <h4>Email</h4>
             <p><a href="mailto:info@ceatsrilanka.com">info@ceatsrilanka.com</a></p>
            </div>
          </div>
        </div>
        
        <div class="contact-form">
          <form id="contact-form">
            <h2>Send Message</h2>
            <div class="input-box">
              <input type="text" required="true" name="name">
              <span>Full Name</span>
            </div>
            
            <div class="input-box">
              <input type="email" required="true" name="email" id="email">
              <span>Email</span>
            </div>

            <div class="input-box">
              <textarea required="true" name="message"></textarea>
              <span>Type your Message...</span>
            </div>
            
            <div class="input-box">
              <input type="submit" value="Send">
            </div>
          </form>
        </div>
        
        <script>
          document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('send_data.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                Swal.fire({
                  title: 'Success!',
                  text: data.message,
                  icon: 'success',
                  confirmButtonText: 'OK'
                }).then(() => {
                  window.location.reload(); // Reload the page after user clicks 'OK'
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: data.message,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            })
            .catch(error => {
              Swal.fire({
                title: 'Error!',
                text: 'An error occurred while submitting the form.',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            });
          });



          //new code
          
        // WebSocket connection
        const ws = new WebSocket('ws://localhost:8080');

        ws.onopen = () => {
            console.log('Connected to WebSocket server');
        };

        ws.onmessage = event => {
            const data = JSON.parse(event.data);
            showNotification(data.title, data.message);
        };

        ws.onclose = () => {
            console.log('Disconnected from WebSocket server');
        };

        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            var formData = new FormData(this);

            fetch('send_data.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                if (data.includes('success')) {
                    showNotification('Success', 'Contact form submitted successfully!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // Reload the page after 2 seconds
                } else {
                    alert('Failed to submit the contact form. Please try again.');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });

        function showNotification(title, message) {
            if (Notification.permission === 'granted') {
                new Notification(title, {
                    body: message
                });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        new Notification(title, {
                            body: message
                        });
                    }
                });
            }
        }

        // Request notification permission on page load
        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    
        </script>
      </div>
    </div>
  </section>
</body>
</html>
