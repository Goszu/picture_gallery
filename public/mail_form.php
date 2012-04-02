<script type="text/javascript">
    $(function() {
        $("#send").click(function() {
            $.post("mail.php", { imie: $("#imie").val(), tel: $("#tel").val(), mail: $("#mail").val(), wiad: $("#wiad").val() }, function(data) {
                $(".txt_formularz").html(data)
            })
            return false;
        });
    });
</script>

<form id="form">
    <div><input type="text" onblur="if(this.value=='') this.value='Your Name:'" onfocus="if(this.value =='Your Name:' ) this.value=''" name="imie" id="imie" value="Your Name:"  /></div>
    <div><input type="text" onblur="if(this.value=='') this.value='Telephone no.:'" onfocus="if(this.value =='Telephone no.:' ) this.value=''" name="tel" id="tel" value="Telephone no.:"  /></div>
    <div><input type="text" onblur="if(this.value=='') this.value='E-mail:'" onfocus="if(this.value =='E-mail:' ) this.value=''" name="mail" id="mail" value="E-mail:"  /></div>
    <div><textarea rows="40" cols="30"  onblur="if(this.value=='') this.value='Message:'" onfocus="if(this.value =='Message:' ) this.value=''" name="wiad" id="wiad">Message:</textarea></div>
    <div><a href="#" class="link"  onclick="document.getElementById('form').reset()">reset</a> &nbsp; &nbsp; &nbsp;<a href="#" class="link" id="send">Send</a></div>
</form>