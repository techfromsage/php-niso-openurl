<?php

class OpenURLTest extends \PHPUnit_Framework_TestCase {
    public function testLoadKev() {
        $kev = "url_ver=Z39.88-2004&url_tim=2003-04-11T10%3A09%3A15TZD&url_ctx_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Actx&ctx_ver=Z39.88-2004&ctx_enc=info%3Aofi%2Fenc%3AUTF-8&ctx_id=10_8&ctx_tim=2003-04-11T10%3A08%3A30TZD&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rft.genre=book&rft.aulast=Vergnaud&rft.auinit=J.-R.&rft.btitle=D%C3%A9pendances+et+niveaux+de+repr%C3%A9sentation+en+syntaxe&rft.date=1985&rft.pub=Benjamins&rft.place=Amsterdam%2C+Philadelphia&rfe_id=urn%3Aisbn%3A0262531283&rfe_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Abook&rfe.genre=book&rfe.aulast=Chomsky&rfe.auinit=N&rfe.btitle=The+Minimalist+Program&rfe.isbn=0262531283&rfe.date=1995&rfe.pub=The+MIT+Press&rfe.place=Cambridge%2C+Mass&svc_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Asch_svc&svc.abstract=yes&rfr_id=info%3Asid%2Febookco.com%3Abookreader";
        $ctx = \OpenURL\ContextObject::loadKev($kev);
        $this->assertEquals("Z39.88-2004", $ctx->getVersion());
        $this->assertEquals("info:ofi/enc:UTF-8", $ctx->getEncoding());
        $this->assertEquals("10_8", $ctx->getIdentifier());
        $this->assertEquals("2003-04-11T10:08:30TZD", $ctx->getTimestamp());
        $this->assertEquals("info:ofi/fmt:kev:mtx:book", $ctx->getReferent()->getValFormat());
        $this->assertEquals("book", $ctx->getReferent()->getValue('genre'));
        $this->assertEquals("Vergnaud", $ctx->getReferent()->getValue('aulast'));
        $this->assertEquals("J.-R.", $ctx->getReferent()->getValue('auinit'));
        $this->assertEquals("Dépendances et niveaux de représentation en syntaxe", $ctx->getReferent()->getValue('btitle'));
        $this->assertEquals("1985", $ctx->getReferent()->getValue('date'));
        $this->assertEquals("Benjamins", $ctx->getReferent()->getValue('pub'));
        $this->assertEquals("Amsterdam, Philadelphia", $ctx->getReferent()->getValue('place'));
        $this->assertEquals("urn:isbn:0262531283", $ctx->getReferringEntity()->getIdentifier());
        $this->assertEquals("info:ofi/fmt:kev:mtx:book", $ctx->getReferringEntity()->getValFormat());
        $this->assertEquals("book", $ctx->getReferringEntity()->getValue('genre'));
        $this->assertEquals("Chomsky", $ctx->getReferringEntity()->getValue('aulast'));
        $this->assertEquals("N", $ctx->getReferringEntity()->getValue('auinit'));
        $this->assertEquals("The Minimalist Program", $ctx->getReferringEntity()->getValue('btitle'));
        $this->assertEquals("0262531283", $ctx->getReferringEntity()->getValue('isbn'));
        $this->assertEquals("1995", $ctx->getReferringEntity()->getValue('date'));
        $this->assertEquals("The MIT Press", $ctx->getReferringEntity()->getValue('pub'));
        $this->assertEquals("Cambridge, Mass", $ctx->getReferringEntity()->getValue('place'));
        $svc = $ctx->getServiceType();
        $this->assertEquals("info:ofi/fmt:kev:mtx:sch_svc", $svc[0]->getValFormat());
        $this->assertEquals("yes", $svc[0]->getValue('abstract'));
        $this->assertEquals("info:sid/ebookco.com:bookreader", $ctx->getReferrer()->getIdentifier());
    }

    public function testEntityUnsetForAllValues()
    {
        $entity = new \OpenURL\Entity();
        $entity->setValue('key1', 'val1');
        $entity->setValue('key2', 'val2');

        $entity->unsetValue('key2');

        $key2Values = $entity->getValue('key2');
        $this->assertNull($key2Values);
        $this->assertEquals('val1', $entity->getValue('key1'));
        $this->assertEquals(array('key1'=>'val1'), $entity->getValues());
    }

    public function testEntityUnsetValueForSingleValue()
    {
        $entity = new \OpenURL\Entity();
        $entity->setValue('key1', array('val1a', 'val1b'));
        $entity->setValue('key2', 'val2');

        $entity->unsetValue('key1', 'val1a');

        $this->assertEquals(array('key1'=>array('val1b'), 'key2'=>'val2'), $entity->getValues());
    }

    public function testEntityUnsetValueWithNonExistentValue()
    {
        $entity = new \OpenURL\Entity();
        $entity->setValue('key1', array('val1a', 'val1b'));
        $entity->setValue('key2', 'val2');

        $entity->unsetValue('key1', 'foo');

        $this->assertEquals(array('key1'=>array('val1a', 'val1b'), 'key2'=>'val2'), $entity->getValues());
    }

    public function testEntityUnsetValueWithNonExistentKey()
    {
        $entity = new \OpenURL\Entity();
        $entity->setValue('key1', 'val1');
        $entity->setValue('key2', 'val2');
        $entity->unsetValue('keyFoo');

        $this->assertEquals(array('key1'=>'val1', 'key2'=>'val2'), $entity->getValues());
    }



}