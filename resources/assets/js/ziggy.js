    var Ziggy = {
        namedRoutes: {"login":{"uri":"login","methods":["GET","HEAD"],"domain":null},"logout":{"uri":"logout","methods":["POST"],"domain":null},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"],"domain":null},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"domain":null},"password.email":{"uri":"forgot-password","methods":["POST"],"domain":null},"password.update":{"uri":"reset-password","methods":["POST"],"domain":null},"register":{"uri":"register","methods":["GET","HEAD"],"domain":null},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"],"domain":null},"user-password.update":{"uri":"user\/password","methods":["PUT"],"domain":null},"password.confirm":{"uri":"user\/confirm-password","methods":["GET","HEAD"],"domain":null},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"],"domain":null},"two-factor.login":{"uri":"two-factor-challenge","methods":["GET","HEAD"],"domain":null},"profile.show":{"uri":"user\/profile","methods":["GET","HEAD"],"domain":null},"other-browser-sessions.destroy":{"uri":"user\/other-browser-sessions","methods":["DELETE"],"domain":null},"current-user.destroy":{"uri":"user","methods":["DELETE"],"domain":null},"current-user-photo.destroy":{"uri":"user\/profile-photo","methods":["DELETE"],"domain":null},"teams.create":{"uri":"teams\/create","methods":["GET","HEAD"],"domain":null},"teams.store":{"uri":"teams","methods":["POST"],"domain":null},"teams.show":{"uri":"teams\/{team}","methods":["GET","HEAD"],"domain":null},"teams.update":{"uri":"teams\/{team}","methods":["PUT"],"domain":null},"teams.destroy":{"uri":"teams\/{team}","methods":["DELETE"],"domain":null},"current-team.update":{"uri":"current-team","methods":["PUT"],"domain":null},"team-members.store":{"uri":"teams\/{team}\/members","methods":["POST"],"domain":null},"team-members.update":{"uri":"teams\/{team}\/members\/{user}","methods":["PUT"],"domain":null},"team-members.destroy":{"uri":"teams\/{team}\/members\/{user}","methods":["DELETE"],"domain":null},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"],"domain":null},"settings.users":{"uri":"settings\/users","methods":["GET","HEAD"],"domain":null},"settings.user_edit":{"uri":"settings\/user\/{user}\/edit","methods":["GET","HEAD"],"domain":null},"workorders":{"uri":"workorders","methods":["GET","HEAD"],"domain":null}},
        baseUrl: 'http://sequoia.me/',
        baseProtocol: 'http',
        baseDomain: 'sequoia.me',
        basePort: false,
        defaultParameters: []
    };

    if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
        for (var name in window.Ziggy.namedRoutes) {
            Ziggy.namedRoutes[name] = window.Ziggy.namedRoutes[name];
        }
    }

    export {
        Ziggy
    }
