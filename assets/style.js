

$(document).ready(function () {

    $('.myDIVcomload').on('click', function (e) {
        $fluxId = $(this).data('flux');
        //print ($fluxId);
        $x = document.getElementById("myDIVcomload-" + $fluxId);
        if ($x.style.display === "none") {
            $x.style.display = "block";
        } else {
            $x.style.display = "none";
        }
    });

    $('.myDIVnew').on('click', function (e) {
        $fluxId = $(this).data('flux');
        $x = document.getElementById("myDIVnew-" + $fluxId);

        if ($x.style.display === "none") {
            $x.style.display = "block";
        } else {
            $x.style.display = "none";
        }
    });

    $('.like-post').on('click', function (e) {
        e.preventDefault();
        $link = $(this);
        $.ajax({
            type: "POST",
            url: $link.attr('href'),
            success: function (response) {
                $link.find('i').toggleClass('fa-heart');
                $link.find('i').toggleClass('fa-heart-o');
                if (response < 1) {
                    $('.nbrlikes-'+ $link.data('flux')).html("");
                }
                $('.nbrlikes-'+ $link.data('flux')).html("<small><strong>" + response +"</strong> J'aime</small>");
            }
        });
    });

    $('.suivis').on('click', function (e) {
        e.preventDefault();
        $link = $(this);
        $.ajax({
            type: "POST",
            url: $link.attr('href'),
            success: function (response) {
                $link.find('i').toggleClass('fa-star');
                $link.find('i').toggleClass('fa-star gold');
                $link.parent('div').toggleClass("rating_no");
                $link.parent('div').toggleClass("rating");
                if (response == 1) {
                    $('.infosuiveur').html(" Jardinier suivi");
                }
                else {
                    $('.infosuiveur').html(" Suivre ce jardinier");
                }
            }
        });
    });

    function display_photo(p_name,p_w,p_h,p_legend) {
        if (self.innerWidth) {
            winwidth = self.innerWidth;
            winheight = self.innerHeight;
        }
        else if (document.documentElement && document.documentElement.clientWidth) {
            winwidth = document.documentElement.clientWidth;
            winheight = document.documentElement.clientHeight;
        }
        else if (document.body) {
            winwidth = document.body.clientWidth;
            winheight = document.body.clientHeight;
        }
        if (Number(p_w) < winwidth) winwidth = Number(p_w);
        if (Number(p_h) < winheight) winheight = Number(p_h);
        winwidth += 8; winheight += 40;
        pwin=window.open("","","toolbar=0,location=0,directories=0,status=0,menubar=0,resizable=1,scrollbars=yes,copyhistory=0,width="+winwidth+",height="+winheight+",left=10,top=10" );
        pwin.document.write("<html><head>" );
        pwin.document.write("<title>Zoom</title>" );
        pwin.document.write("<style>" );
        pwin.document.write("body {" );
        pwin.document.write("margin:0;" );
        pwin.document.write("padding:0;" );
        pwin.document.write("color:white;" );
        pwin.document.write("background-color:black; }" );
        pwin.document.write("</style>" );
        pwin.document.write("</head>" );
        pwin.document.write("<body>" );
        pwin.document.write("<img src="+p_name+" width="+p_w+" height="+p_h+">" );
        pwin.document.write("<table><tr>" );
        pwin.document.write("<form><td>"+p_legend+"</td>" );
        pwin.document.write("<td><input type='button' value='Fermer' onClick='window.close()'></td>" );
        pwin.document.write("</tr></table></form>" );
        pwin.document.write("</body></html>" );
    }

    const specifications = document.querySelectorAll('.specifications');
    specifications.forEach(
        el => el.addEventListener(
            'toggle',
            event => {
                const specification = event.target;
                const chevron = specification.querySelector('.chevron');
               // console.log(event.target.getAttribute("id"));
                if (specification.open) {
                //    console.log('La spécification "' + specification.getAttribute("id") + '" est ouverte !');
                    chevron.className = 'chevron glyphicon glyphicon-menu-down';
                } else {
                //    console.log('La spécification "' + specification.getAttribute("id") + '" est fermée !');
                    chevron.className = 'chevron glyphicon glyphicon-menu-right';
                }
            },
            false
        )
    );



    $('.add-comment').on('click', function () {
        $link = $(this);
        $.ajax({
            type : "POST",
            url : $link.data('url'),
            data : {'contenu': $link.siblings('textarea').val()},
            success : function(response) {
                //alert('Commentaire posté!');
                $link.siblings('textarea').val("");
                $('.myDIVcom-'+ $link.data('flux')).append("<p class=\"zonecom iteration\">" +
                    "<span class=\"menufloatl\">" +
                    "<strong> " + response.user + " </strong>" + response.contenu  + "<br/></span>&nbsp; <span class=\"dateheure\"> le " +  response.date + " </span><br/></p>");
            }
        });
    });
});

