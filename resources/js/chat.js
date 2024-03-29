const message = $('#messageInput')
const messages = $('.messages')

// Check if null and return to main
try {
    // Broadcasting messages
    message.value = message.value ? message.value : 'Tell me about your issue'
    let broadcast = false
    let code = 'xxx'
    $('#messageForm').on('submit', (e) => {
        e.preventDefault();
        // Sender
        code = $('.left.message code').textContent
        if (message !== '') {
            // console.log('Before', (broadcast? 'true': 'false'))
            broadcast = !broadcast
            window.axios.post('/broadcast', {
                message: message.value,
                code,
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
                code: event.code,
                broadcast
            }).then(res => {
                messages.innerHTML += res.data
            }).catch(error => {
                console.error('Error receiving message:', error);
            });
        }
        // console.log('After event ctrl', (broadcast? 'true': 'false'))
    });
} catch (err) {
    console.log(err)
}

export async function loadUserChatHistory(userId) {
    await window.axios.get(`/api/chat-history?user=${userId}`
    ).then(res => {
        console.log('called')
        const chatHistory = res.data.sort((a, b) => {
                // Convert timestamp strings to Date objects for comparison
                const timestampA = new Date(a.timestamp);
                const timestampB = new Date(b.timestamp);
        
                if (timestampA < timestampB) {
                    return -1;
                } else if (timestampA > timestampB) {
                    return 1;
                } else {
                    return 0;
                }
            })
        // Get instance of sender and receiver message html for reuse
        const sender = `<div class="left message">
            <img src="data:image/jpeg;base64,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" alt="LEFT">
            <code></code>
            <p></p>
        </div>`

        const receiver = `<div class="right message">
            <p></p>
            <code></code>
            <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f" alt="">
        </div>`

        // Update messages box
        $('.messages').text('')
        let side = ''
        chatHistory.map(message => {
            if($.cookie("agent_id")) {
                // Agent only message
                if(message.agent_id && !message.user_id) {
                    side = 'left'
                    const sender = `<div class="${side} message">
                        <img src="data:image/jpeg;base64,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" alt="LEFT">
                        <code>${message.agent_id}</code>
                        <p>${message.ticket_body}</p>
                    </div>`
                } else {
                    
                }
            } else {
                const receiver = `<div class="${side} message">
                    <p>${message.body}</p>
                    <code>${message.user_id}</code>
                    <img src="https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436200.jpg?w=740&t=st=1707563581~exp=1707564181~hmac=3ef67fa5fc1d8d2b4aff9fa2fdc9f2dedaab7ddf873e3c4fb98a8276be64b91f" alt="">
                </div>`
            }
            console.log(newMessage)
            $('.messages').append(newMessage)
        })
    }).catch(error => {
        console.log("Error load user chat history: "+ error)
    })
}

export default loadUserChatHistory;