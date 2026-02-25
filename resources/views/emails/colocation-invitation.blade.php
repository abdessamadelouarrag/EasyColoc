<p>Bonjour,</p>

<p>Tu as reçu une invitation pour rejoindre la colocation : <b>{{ $invitation->colocation->name }}</b>.</p>

<p>
    Clique sur ce lien pour accepter :
    <a href="{{ route('invitations.accept', $invitation->token) }}">Accepter l’invitation</a>
</p>

<p>Si tu n’es pas concerné, ignore cet email.</p>