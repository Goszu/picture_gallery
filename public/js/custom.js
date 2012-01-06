$("#list ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
	var order = $(this).sortable("serialize") + '&update=update';
    //console.log(order);
    $.post("sortusers.php", order, function(theResponse){
        $("#response").html(theResponse);
        $("#response").slideDown('slow');
    });
}
});
