<h2>You have been granted access as a<?= !empty($volunteer['RescueVolunteer']['admin']) ? "n administrator": " volunteer" ?> for <?= $rescue['Rescue']['title'] ?></h2>

<?= !empty($message) ? "<p>$message" : "" ?>

<p>Use the link below to continue:

<?
$login = !empty($user['User']['invite']) ? "/users/invite/".urlencode($user['User']['email'])."/{$user['User']['invite']}" : "/user/users/login";

if($this->Rescue->dedicated($rescue)) {  # seems to work but loses hostname...
	$url = $this->Rescue->url($rescue, $login,false);
} else {
	$url = "$login?redirect=".$this->Rescue->url($rescue,null,false);
}

?>

<p><?= $this->Html->link(Router::url($url,true)); ?></p>
