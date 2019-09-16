import { TestBed } from "@angular/core/testing";
import { TagsPipe } from './tags';

describe('TagPipe', () => {

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [TagsPipe],
    });
  });

  it('should transform when # in the middle ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring#name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="/newsfeed/tag/name;ref=hashtag');
  });

  it('should transform when # preceded by space ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring #name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="/newsfeed/tag/name;ref=hashtag');
  });

  it('should transform when # preceded by [] ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring [#name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="/newsfeed/tag/name;ref=hashtag');
  });

  it('should transform when # preceded by () ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring (#name)';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="/newsfeed/tag/name;ref=hashtag');
  });


  it('should correctly parse when duplicates substrings present', () => {
    const pipe = new TagsPipe();
    const string = '#hash #hashlonger';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="/newsfeed/tag/hash;ref=hashtag');
    expect(transformedString).toContain('<a href="/newsfeed/tag/hashlonger;ref=hashtag');
  });

  it('should transform when @ preceded by () ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring (@name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a class="tag"');
  });

  it('should transform when @ preceded by [] ', () => {
    const pipe = new TagsPipe();
    const string = 'textstring [@name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a class="tag"');
  });

  it('should transform when @ preceded by space', () => {
    const pipe = new TagsPipe();
    const string = 'textstring @name';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a class="tag"');
  });

  it('should transform to an email', () => {
    const pipe = new TagsPipe();
    const string = 'textstring@name.com';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="mailto:textstring@name.com"');
  });

  it('should not transform when @ not present', () => {
    const pipe = new TagsPipe();
    const string = 'textstring name';
    const transformedString = pipe.transform(<any>string);

    expect(transformedString).toEqual(string);
    expect(transformedString).not.toContain('<a class="tag"');
  });

  it('should transform url http', () => {
    const pipe = new TagsPipe();
    const string = 'textstring http://ops.doesntexist.com/';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="http://ops.doesntexist.com/');
  });

  it('should transform url with https', () => {
    const pipe = new TagsPipe();
    const string = 'textstring https://ops.doesntexist.com/';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="https://ops.doesntexist.com/');
  });

  it('should transform url with ftp', () => {
    const pipe = new TagsPipe();
    const string = 'textstring ftp://ops.doesntexist.com/';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="ftp://ops.doesntexist.com/');
  });

  it('should transform url with file', () => {
    const pipe = new TagsPipe();
    const string = 'textstring file://ops.doesntexist.com/';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('<a href="file://ops.doesntexist.com/');
  });

  it('should transform url with a hashtag', () => {
    const pipe = new TagsPipe();
    const string = 'text http://ops.doesntexist.com/#position';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('text <a href="http://ops.doesntexist.com/#position"');
  });

  it('should transform url with a hashtag and @', () => {
    const pipe = new TagsPipe();
    const string = 'text http://ops.doesntexist.com/#position@some';
    const transformedString = pipe.transform(<any>string);
    expect(transformedString).toContain('text <a href="http://ops.doesntexist.com/#position@some"');
  });

  it('should transform many tags', () => {
    const pipe = new TagsPipe();
    const string = `text http://ops.doesntexist.com/#position@some @name
    @name1 #hash1#hash2 #hash3 ftp://s.com name@mail.com
    `;
    const transformedString = pipe.transform(<any>string);

    expect(transformedString).toContain('<a href="http://ops.doesntexist.com/#position@some"');
    expect(transformedString).toContain('<a class="tag" href="/name"');
    expect(transformedString).toContain('<a class="tag" href="/name1"');
    expect(transformedString).toContain('<a href="/newsfeed/tag/hash1;ref=hashtag');
    expect(transformedString).toContain('<a href="/newsfeed/tag/hash2;ref=hashtag');
    expect(transformedString).toContain('<a href="/newsfeed/tag/hash3;ref=hashtag');
    expect(transformedString).toContain('<a href="ftp://s.com"');
    expect(transformedString).toContain('<a href="mailto:name@mail.com"');
  });
});
