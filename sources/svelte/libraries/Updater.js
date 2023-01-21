/*
    interface iupdate
    {
        update();
    }
*/
class Updater {

   attach = (name, iupdate) => {
      if (['attach', 'exists'].includes(name)) throw Error('reserved name')
      if (typeof iupdate !== 'object' || typeof iupdate.update !== 'function') throw Error('interface missmatch: method `update()` required.')
      this[name] = iupdate
   }

   exists = (name) => {
      return typeof this[name] !== 'undefined'
   }

}

export const updater = new Updater()