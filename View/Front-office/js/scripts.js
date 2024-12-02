$(document).ready(function() {
    // Load contacts into the sidebar
    contacts.forEach(contact => {
        $('#contacts-list').append(`<li data-id="${contact.id}" data-username="${contact.username}">${contact.username}</li>`);
    });

    // Handle contact click
    $('#contacts-list').on('click', 'li', function() {
        const contactId = $(this).data('id');
        const contactName = $(this).data('username');
        $('#contact-name').text(contactName);
        $('#contacts-list li').removeClass('active');
        $(this).addClass('active');
        loadConversation(contactId);
    });

    function loadConversation(contactId) {
        $.ajax({
            url: 'include/get_conversation.php',
            method: 'POST',
            data: { receiverId: contactId },
            success: function(response) {
                $('#conversation').html(response);
            },
            error: function() {
                alert('Error loading conversation');
            }
        });
    }

    // Handle message form submission
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('receiverId', $('#contacts-list li.active').data('id'));

        $.ajax({
            url: 'include/send_message.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                loadConversation($('#contacts-list li.active').data('id'));
                $('#message-form')[0].reset();
            },
            error: function() {
                alert('Error sending message');
            }
        });
    });
});