php-niso-openurl
================

A php client library for parsing and generating NISO Z39.88 (OpenURL) context objects

Usage:

```php

$ctx = \OpenURL\ContextObject::loadKev("url_ver=Z39.88-2004&url_tim=2003-04-11T10%3A09%3A15TZD&url_ctx_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&ctx_id=10_8&ctx_tim=2003-04-11T10%3A08%3A30TZD&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rft.genre=book&rft.aulast=Vergnaud&rft.auinit=J.-R.&rft.btitle=D%C3%A9pendances+et+niveaux+de+repr%C3%A9sentation+en+syntaxe&rft.date=1985&rft.pub=Benjamins&rft.place=Amsterdam%2C+Philadelphia&rfe_id=urn%3Aisbn%3A0262531283&rfe_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rfe.genre=book&rfe.aulast=Chomsky&rfe.auinit=N&rfe.btitle=The+Minimalist+Program&rfe.isbn=0262531283&rfe.date=1995&rfe.pub=The+MIT+Press&rfe.place=Cambridge%2C+Mass&svc_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Asch_svc&svc.abstract=yes&rfr_id=info%3Asid%2Febookco.com%3Abookreader");

echo $ctx->getReferent()->getValue('btitle');

=> "Dépendances et niveaux de représentation en syntaxe"

echo $ctx->getReferringEntity()->getValue('aulast');

=> "Chomsky"

echo $ctx->toKev();

=> "url_ver=Z39.88-2004&url_tim=2003-04-11T10%3A09%3A15TZD&url_ctx_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&ctx_id=10_8&ctx_tim=2003-04-11T10%3A08%3A30TZD&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rft.genre=book&rft.aulast=Vergnaud&rft.auinit=J.-R.&rft.btitle=D%C3%A9pendances+et+niveaux+de+repr%C3%A9sentation+en+syntaxe&rft.date=1985&rft.pub=Benjamins&rft.place=Amsterdam%2C+Philadelphia&rfe_id=urn%3Aisbn%3A0262531283&rfe_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rfe.genre=book&rfe.aulast=Chomsky&rfe.auinit=N&rfe.btitle=The+Minimalist+Program&rfe.isbn=0262531283&rfe.date=1995&rfe.pub=The+MIT+Press&rfe.place=Cambridge%2C+Mass&svc_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Asch_svc&svc.abstract=yes&rfr_id=info%3Asid%2Febookco.com%3Abookreader"
