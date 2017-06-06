define(
    [
        'emberjs'
    ],
    function (Ember) {

        var t = 1;

        return Ember.Object.extend({
            handle: function (listeningEditor, newValue, property, listenerName) {

                if (newValue.substr(10,1) === '-') {
                    Typo3Neos.Notification.info('Alias "'+newValue.substr(11)+'" ist bereits vorhanden und wurde automatisch ersetzt durch "'+newValue+'". Bitte geben Sie einen eindeutigen Bezeichner ein.');
                }

            }
        });
    });