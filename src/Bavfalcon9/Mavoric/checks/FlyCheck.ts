import { Player, EventChannel, MovePlayerPacket } from 'netrex';
import Check from './Check.ts';

class FlyCheck extends Check {
     public ready(): void {
          EventChannel.hook(this.plugin, 'DataPacket', this.handleMove, { type: 'MovePlayerPacket' });
     }

     public unready(): void {
          EventChannel.unhook(this.plugin, this.handleMove);
     }

     private handleMove(player: Player, packet: MovePlayerPacket): void {
          // check on ground spoof
          if (packet.onGround !== player.isOnGround()) {
               player.kick('On Ground spoof detected!');
          }
          if (!player.isOnGround()) {
               if (packet.y > player.y && !player.hasBlockBeneath()) {
                    player.kick('Stop flying');
               }
          }
     }
}
export default FlyCheck;