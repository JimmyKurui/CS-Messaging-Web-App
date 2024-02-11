import { loadUserChatHistory } from "./chat";
import createDefaultTicket from "./ticket";

newMessagesNotify()

$('.notification').on('click', function(event){
    event.stopPropagation();
    $('.dropdown-menu').toggleClass('show');
  });
  
  // Close Dropdown on Click Outside
  $(document).on('click', function(event) {
    if (!$(event.target).closest('.notification').length) {
      $('.dropdown-menu').removeClass('show');
    }
  });

async function newMessagesNotify() {
    await window.axios.get('/api/messages')
    .then(messages => {
        const userIdCount = messages.data.reduce((countMap, obj) => {
            const userId = obj.user_id;
            countMap[userId] = (countMap[userId] || 0) + 1;
            return countMap;
        }, {});
        // New messages sum
        $('.badge.badge-danger').text('Notifications ' + Object.values(userIdCount).reduce((total, count) => total + count, 0))
        // Append each user with respective new messages sum
        Object.keys(userIdCount).forEach(userId => {
            const dropdownItem = $('<button>').addClass('dropdown-item').attr('type', 'submit').val(userId).text(userId).append($('<span>').addClass('badge badge-primary').text(userIdCount[userId]));
            $('.dropdown-menu').append(dropdownItem);
            // $('.dropdown-menu')//.append($('<span>').addClass('badge badge-primary').text(userIdCount[userId]));
        })
        $('button.dropdown-item').click(function (e) {
          e.preventDefault();
          loadUserChatHistory(parseInt(event.target.value));
          // createDefaultTicket(e);
        })
    }).catch(error => {
        console.error('Error in notify messages: ', error);
    });
}