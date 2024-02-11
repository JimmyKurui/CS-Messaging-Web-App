const messageForm = document.querySelector('#messageForm')
const message = document.querySelector('#messageInput')
const sender = document.querySelector('.left.message')
const receiver = document.querySelector('.right.message')
const messages = document.querySelector('.messages')

// Check if null and return to main
try {
    message.value = message.value ? message.value : 'Tell me about your issue'
    let broadcast = false

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Broadcasting messages
        if (message !== '') {
            // console.log('Before', (broadcast? 'true': 'false'))
            broadcast = !broadcast
            window.axios.post('/broadcast', {
                message: message.value,
                broadcast
            }).then(res => {
                messages.innerHTML += res.data
                broadcast = false
            }).catch(error => {
                console.error('Error broadcasting message:', error);
            });
            // console.log('After', (broadcast? 'true': 'false'))
        }
    });

    // Receiving messages
    window.Echo.channel('public').listen('.chat', (event) => {
        // console.log('Before event ctrl', (broadcast? 'true': 'false'))
        if(broadcast === false) {
            
            window.axios.post('/broadcast', {
                message: event.message,
                broadcast
            }).then(res => {
                messages.innerHTML += res.data
            }).catch(error => {
                console.error('Error receiving message:', error);
            });
        }
        // console.log('After event ctrl', (broadcast? 'true': 'false'))
    });
     
} catch (err) {}