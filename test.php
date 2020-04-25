<?php

namespace Classes;

require_once ('classes/ChatStatuses.php');

return $transitions = [
   ChatStatuses::WELCOME  => [
        'from' => [

        ],
        'to' => [

        ]
    ]
];

function compareStatuses($_currChatStatus, $_prevChatStatus)
{
    $currChatStatus = ChatStatuses::selectCurrChatStatus($this->db, $this->chatId);
    $prevChatStatus = ChatStatuses::selectPrevChatStatus($this->db, $this->chatId);

    if ($currChatStatus == $_currChatStatus && $prevChatStatus == $_prevChatStatus) {

        return 1;
    }
}