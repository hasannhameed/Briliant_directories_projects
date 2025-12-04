<div class="callback-form-container">
    <div class="modal-header">
        <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Demande de rappel</h3>
    </div>

    <div class="modal-body">
        <form class="form-horizontal">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="nom" class="control-label required">Nom *</label>
                        <input type="text" class="form-control" id="nom" placeholder="Votre nom">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="prenom" class="control-label required">Prénom *</label>
                        <input type="text" class="form-control" id="prenom" placeholder="Votre prénom">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email" class="control-label required">Email *</label>
                        <input type="email" class="form-control" id="email" placeholder="votre@email.fr">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="telephone" class="control-label required">Téléphone *</label>
                        <input type="tel" class="form-control" id="telephone" placeholder="06 12 34 56 78">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="code-postal" class="control-label required">Code postal *</label>
                        <input type="text" class="form-control" id="code-postal" placeholder="01000">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="ville" class="control-label required">Ville *</label>
                        <input type="text" class="form-control" id="ville" placeholder="Votre ville">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="type-batiment" class="control-label required">Type de bâtiment *</label>
                        <select class="form-control" id="type-batiment">
                            <option>Choisir</option>
                            </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="type-travaux" class="control-label required">Type de travaux envisagés *</label>
                        <select class="form-control" id="type-travaux">
                            <option>Choisir</option>
                            </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="budget" class="control-label">Budget estimé</label>
                        <select class="form-control" id="budget">
                            <option>Choisir</option>
                            </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="delai" class="control-label">Délai souhaité</label>
                        <select class="form-control" id="delai">
                            <option>Choisir</option>
                            </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="message" class="control-label">Votre message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Décrivez votre projet, vos questions..."></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer-custom">
                <button type="submit" class="btn btn-lg btn-block custom-red-submit">
                    <span class="glyphicon glyphicon-phone-alt"></span> Demander à être rappelé
                </button>
            </div>
        </form>
    </div>
</div>