class Utils {

    static bytes_to_size(bytes) {
        let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0';
        let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }

    static decode_html(html) {
        let txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }

    static getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++)
        {
            var c = ca[i].trim();
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    static setCookie(name, value, exdays) {
        var exdate = new Date();

        exdate.setDate(exdate.getDate() + exdays);

        value = escape(value) + ((exdays == null) ? '' : '; expires=' + exdate.toUTCString());
        document.cookie = name + '=' + value + '; path=/;';
    }

    static setCookieMinutes (name, value, exminutes) {
        var exdate = new Date();
        exdate = new Date(exdate.getTime() + exminutes*60000);

        value = escape(value) + ((exminutes == null) ? '' : '; expires=' + exdate.toUTCString());
        document.cookie = name + '=' + value + '; path=/;';
    }
}

module.exports = Utils;