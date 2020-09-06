import { Plugin } from 'netrex';
import FlyCheck from "./checks/FlyCheck.ts";
// this is just test implementation and is no where near production ready lol
class Mavoric extends Plugin {
     public checks!: Check[];

     public onEnable(): void {
          (this.checks = [
               new FlyCheck(this)
          ]).forEach(check => {
               check.ready();
          });
          this.getLogger().notice('Enabled Mavoric for Netrex');
     }

     public onDisable(): void {
          this.checks.forEach(check => {
               check.unready();
          });
     }

     public onReload(): void {
          this.onDisable();
          this.onEnable();
     }
}

export default Mavoric;