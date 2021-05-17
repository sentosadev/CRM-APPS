<?php

function response_success($message, $data = NULL)
{
  $response = ['status' => 1, 'message' => $message, 'data' => $data];
  send_json($response);
}
function response_error($message)
{
  $response = ['status' => 0, 'message' => $message];
  send_json($response);
}

function base_url_sql($field)
{
  return "CONCAT('" . base_url() . "',$field)";
}
