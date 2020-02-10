$(function(){
    //Quand on clique sur le bouton suprimmer
    $('.delete').click (function() {
        //récupère l'id du bouton
        var id = (this.id);
        var href = '#?id='+id;
        //ouvre la modal
        $('#deleteappointment').modal('show');
        // ajoute une valeur à l'id du bouton oui
        $('.definitiveDelete').attr('id', id);
        // ajoute une valeur au href du bouton oui
        $('.definitiveDelete').attr('href', href )
    });
    //Quand on clique sur le bouton oui
    $('.definitiveDelete').click (function() {
        //donne la valeur isOk au bouton oui
        $('.definitiveDelete').attr('value', 'isOk' );
        //ferme la modal
        $('#deleteappointment').modal('hide');
        // redirige
/*        function redir(){
            self.location.href = '#?id='+id;
        }
        redir();*/
    });
});