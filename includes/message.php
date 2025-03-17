<?php

function message($icon, $title, $text)
{
    echo '<script>
            Swal.fire({
                icon: "' . $icon . '",
                title: "' . $title . '",
                text: "' . $text . '"
            });
        </script>';
        

    unset($_SESSION['message']);
}