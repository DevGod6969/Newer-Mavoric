import Mavoric from "../Mavoric.ts";

abstract class Check {
     protected plugin: Mavoric;

     constructor(plugin: Mavoric) {
          this.plugin = plugin;
     }

     abstract ready(): void;
     abstract unready(): void;
}
export default Check;