<div id="info">
    <h1>Informazioni</h1>
    <h2>Descrizione dell'applicazione</h2>
    <p>
        L'applicazione fornisce all'utente un punto unico di accesso a corsi online erogati
        da siti differenti, quali coursera, edX, pluralsight, digital-tutors, udacity.
        In particolare, gli amministratori possono creare e rimuovere i corsi, mentre gli utenti
        possono ricercare i corsi a cui sono interessati, iscriversi e abbandonarli.
        Quando un amministratore rimuove un corso, tale corso non sarà più accessibile neanche agli utenti ad esso iscritti.
        Per ogni corso, l'applicazione tiene traccia dei seguenti attributi:
    </p>
    <ul>
        <li>Nome</li>
        <li>Link</li>
        <li>Categoria</li>
        <li>Host</li>
        <li>Utente Proprietario</li>
    </ul>
    <p>Ad ogni utente vengono associati:</p>
    <ul>
        <li>Nome</li>
        <li>Cognome</li>
        <li>Email</li>
        <li>Username</li>
        <li>Password</li>
    </ul>
    <h2>Requisiti rispettati</h2>
    <ul>
        <li>Utilizzo HTML e CSS</li>
        <li>Utilizzo di PHP e MySQL</li>
        <li>Utilizzo del pattern MVC</li>
        <li>Due ruoli</li>
        <li>Transazione: metodo removeCourseById della classe CourseFactory</li>
        <li>La funzionalità AJAX è stata implementata per la ricerca dei corsi</li>
    </ul>
    <h2>Accesso al sistema</h2>
    <ul>
        <li>
            <strong>Account Amministratore</strong>
            <ul>
                <li>Username: admin</li>
                <li>Password: moocca</li>
            </ul>
        </li>
        <li>
            <strong>Account Utente</strong>
            <ul>
                <li>Username: user</li>
                <li>Password: moocca</li>
            </ul>
        </li>
    </ul>
    <a href="login" class="button">Home</a>
</div>