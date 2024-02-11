if ($.cookie("agent_id")) {
    try {
        // Ticket API load 
        let tickets = getTickets()
    
        // Ticket API call to create
        $('#ticketForm').submit(function (event) {
            event.preventDefault();
            const status = $('#status').val();
            const priority = $('#priority').val();
            const tags = $('#tags').val();
            const startTime = $('#start-time').val();
            const endTime = $('#end-time').val();
            const title = $('#title').val();
            window.axios.post('/route', {
                status, priority, tags, startTime, endTime, title
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.error(error);
            });
        });
    } catch (error) {
    
    }
}
    async function getTickets() {
        const userId = chatHistory[0].user_id
        await window.axios.get(`/api/tickets${userId ? '?user_id=' + userId : ''}`)
            .then(res => {
                $('#title').val(res.data.title);
                $('#status').val(res.data.status);
                $('#priority').val(res.data.priority);
                $('#category').val(res.data.category);
                $('#description').val(res.data.description);
                $('#tags').val(res.data.tags);
                $('#start-time').val(res.data.startTime);
                $('#end-time').val(res.data.endTime);
            }).catch(error => {
                console.error(error);
            });
    }
    export async function createDefaultTicket(event) {
            const newTicket = {
                title : "Issue title",
                priority_id : 1,
                status_id : 1,
                agent_id : $.cookie("user_id"),
                user_id : event.target.value
            }
            window.axios.post('/api/tickets', newTicket
            ).then(res => {
                console.log(res.data)
                $('#status').val(res.data.status_id);
                $('#priority').val(res.data.priority_id);
                $('#category').val(res.data.category_id);
                $('#tags').val(res.data.tags);
                $('#start-time').val(res.data.start_time);
                $('#end-time').val(res.data.end_time);
                $('#description').val(res.data.description);
                $('#title').val(res.data.title);
            })
        }
    
    
    async function updateTicket() {
        $('ticketForm').on('submit', function (e) {
            e.preventDefault();
            const status_id = parseInt($('#status').val());
            const priority_id = parseInt($('#priority').val());
            const category_id = parseInt($('#category').val());
            const tags = $('#tags').val();
            const startTime = $('#start-time').val();
            const endTime = $('#end-time').val();
            const title = $('#title').val();
            const description = $('#description').val();
    
            window.axios.patch('/api/tickets', {
                status_id,
                priority_id,
                category_id,
                tags,
                description,
                startTime,
                endTime,
                title
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.error(error);
            });
        })
    }

export default createDefaultTicket;