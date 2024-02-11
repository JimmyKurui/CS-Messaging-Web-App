const messageForm = document.querySelector('#messageForm')
const message = document.querySelector('#messageInput')
const sender = document.querySelector('.left.message')
const receiver = document.querySelector('.right.message')
const messages = document.querySelector('.messages')

// Check if null and return to main
try {
    message.value = message.value ? message.value : 'Tell me about it'
    let broadcast = false

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Broadcasting messages
        if (message !== '') {
            broadcast = !broadcast
            window.axios.post('/broadcast', {
                message: message.value,
                broadcast: !broadcast
            }).then(res => {
                messages.innerHTML += res.data
                alert('Success', broadcast? 'true': 'false')
                broadcast = !broadcast
            }).catch(error => {
                console.error('Error broadcasting message:', error);
            });
        }
    });

    // Receiving messages
    window.Echo.channel('public').listen('.chat', (event) => {
        alert('Event', broadcast? 'true': 'false')
        console.log(event.message)
        if(broadcast === false) {

            window.axios.post('/broadcast', {
                message: event.message,
                broadcast: false
            }).then(res => {
                messages.innerHTML += res.data
            }).catch(error => {
                console.error('Error receiving message:', error);
            });
        }
    });
     
} catch (error) {
    console.log(error)
}